<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Artisan;

class DatabaseController extends Controller
{
    public function restoreDatabase(Request $request)
    {
        /*  if ($request->hasFile('database_file')) {
            $file = $request->file('database_file');
            $path = $file->getRealPath();
            $sqlContent = file_get_contents($path);

            // 🔹 استبدال جميع القيم الفارغة '' بـ NULL أو 0 في عمود user_id
            $sqlContent = preg_replace("/VALUES\s*\(\s*''\s*,/", "VALUES (NULL,", $sqlContent);
            $sqlContent = preg_replace("/,\s*''\s*\)/", ", NULL)", $sqlContent);
            $sqlContent = preg_replace("/\(\s*''\s*,/", "(NULL,", $sqlContent);
            $sqlContent = preg_replace("/,\s*''\s*,/", ", NULL,", $sqlContent);

            // 🔹 إعدادات قاعدة البيانات
            $host = env('DB_HOST', '127.0.0.1');
            $username = env('DB_USERNAME', 'root');
            $password = env('DB_PASSWORD', '');
            $database = env('DB_DATABASE');

            // 🔹 الاتصال بقاعدة البيانات باستخدام mysqli
            $mysqli = new \mysqli($host, $username, $password, $database);

            if ($mysqli->connect_error) {
                return back()->with('error', '❌ فشل الاتصال بقاعدة البيانات: ' . $mysqli->connect_error);
            }

            // 🔹 تعطيل التحقق من المفاتيح الأجنبية
            $mysqli->query('SET FOREIGN_KEY_CHECKS=0;');

            // 🔹 تنفيذ جميع استعلامات SQL دفعة واحدة
            if ($mysqli->multi_query($sqlContent)) {
                do {
                    if ($res = $mysqli->store_result()) {
                        $res->free(); // تفريغ النتائج لمنع "Commands out of sync"
                    }
                } while ($mysqli->more_results() && $mysqli->next_result());
            } else {
                return back()->with('error', '❌ خطأ في تنفيذ SQL: ' . $mysqli->error);
            }

            // 🔹 إعادة تفعيل التحقق من المفاتيح الأجنبية
            $mysqli->query('SET FOREIGN_KEY_CHECKS=1;');
            $mysqli->close();


            Artisan::call('migrate', ['--force' => true]);
            return back()->with('success', '✅ تم استعادة قاعدة البيانات بنجاح!');
        } */

        if (app()->environment('local')) {
            if ($request->hasFile('database_file')) {
                $file = $request->file('database_file');
                $path = $file->getRealPath();
                $sqlContent = file_get_contents($path);

                // 🔹 Remove LOCK TABLES and UNLOCK TABLES
                $sqlContent = preg_replace('/LOCK TABLES .*?;/is', '', $sqlContent);
                $sqlContent = preg_replace('/UNLOCK TABLES;/is', '', $sqlContent);

                // 🔹 Replace empty strings '' with NULL in user_id column
                $sqlContent = preg_replace("/\b''\b/", "NULL", $sqlContent);

                try {
                    // 🔹 Execute the entire SQL content at once
                    DB::unprepared($sqlContent);
                } catch (\Exception $e) {
                    return back()->with('error', '❌ SQL execution error: ' . $e->getMessage());
                }

                // 🔹 Run migrations to ensure schema compatibility
                Artisan::call('migrate', ['--force' => true]);

                return back()->with('success', '✅ Database restored successfully!');
            }
            return back()->with('error', '❌ لم يتم العثور على الملف!');
        } else {
            if ($request->hasFile('database_file')) {
                try {
                    // 1. زيادة الحدود الزمنية
                    set_time_limit(3600); // 60 دقيقة
                    ini_set('memory_limit', '2048M');

                    // 2. التحقق من حجم الملف
                    $file = $request->file('database_file');
                    if ($file->getSize() > 1024 * 1024 * 500) { // 500MB
                        return back()->with('error', '❌ حجم الملف يتجاوز الحد المسموح');
                    }

                    // 3. معالجة الملف بشكل تدفقي
                    $stream = fopen($file->getRealPath(), 'r');
                    $sqlContent = '';
                    while (!feof($stream)) {
                        $sqlContent .= fgets($stream);
                    }
                    fclose($stream);

                    // 4. تحسين استبدال القيم الفارغة
                    $sqlContent = preg_replace([
                        "/\b''\b(?=(?:[^\"']*[\"'][^\"']*[\"'])*[^\"']*$)/",
                        "/VALUES\s*\(\s*''\s*,/i",
                        "/,\s*''\s*\)/i",
                        "/\(\s*''\s*,/i",
                        "/,\s*''\s*,/i"
                    ], [
                        'NULL',
                        'VALUES (NULL,',
                        ', NULL)',
                        '(NULL,',
                        ', NULL,'
                    ], $sqlContent);

                    // 5. إعداد اتصال PDO مع مهلات موسعة
                    $pdo = new \PDO(
                        "pgsql:host=" . env('DB_HOST') . ";port=" . env('DB_PORT', 5432) . ";dbname=" . env('DB_DATABASE'),
                        env('DB_USERNAME'),
                        env('DB_PASSWORD'),
                        [
                            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                            \PDO::ATTR_TIMEOUT => 3600, // 60 دقيقة
                            \PDO::ATTR_PERSISTENT => false
                        ]
                    );

                    // 6. تقسيم استعلامات ذكية
                    $queries = [];
                    $delimiter = ';';
                    $buffer = '';
                    $inString = false;
                    $stringChar = '';

                    foreach (str_split($sqlContent) as $char) {
                        if ($char === "'" || $char === '"') {
                            if (!$inString) {
                                $inString = true;
                                $stringChar = $char;
                            } elseif ($char === $stringChar) {
                                $inString = false;
                            }
                        }

                        if ($char === $delimiter && !$inString) {
                            $queries[] = $buffer;
                            $buffer = '';
                        } else {
                            $buffer .= $char;
                        }
                    }
                    $pdo->exec('SET synchronous_commit TO OFF');
                    $pdo->exec('SET maintenance_work_mem TO \'256MB\'');
                    // 7. تنفيذ الاستعلامات مع تسجيل الأخطاء
                    $pdo->beginTransaction();
                    $pdo->exec('SET CONSTRAINTS ALL DEFERRED');

                    foreach ($queries as $index => $query) {
                        $query = trim($query);
                        if (!empty($query)) {
                            try {
                                $pdo->exec($query);
                            } catch (\PDOException $e) {
                                Log::error("فشل الاستعلام #$index: " . $e->getMessage());
                                Log::debug("محتوى الاستعلام: " . $query);
                                throw $e;
                            }
                        }
                    }
                    // بعد التنفيذ
                    $pdo->commit();
                    $pdo->exec('VACUUM ANALYZE');

                    Artisan::call('migrate', ['--force' => true]);
                    return back()->with('success', '✅ تمت الاستعادة بنجاح!');
                } catch (\Exception $e) {
                    if (isset($pdo) && $pdo->inTransaction()) {
                        $pdo->rollBack();
                    }
                    return back()->with('error', '❌ خطأ: ' . $e->getMessage());
                }
            }


            return back()->with('error', '❌ لم يتم العثور على الملف!');
        }
    }
}
