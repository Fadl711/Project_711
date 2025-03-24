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
        if ($request->hasFile('database_file')) {
            try {
                $file = $request->file('database_file');
                $path = $file->getRealPath();

                $fp = fopen($path, 'r');
                $sql = '';
                $queryCount = 0;

                while (!feof($fp)) {
                    $line = fgets($fp);

                    // تجاهل الأسطر غير الضرورية
                    if (trim($line) === '' || preg_match('/^(--|\/\*)/', trim($line))) {
                        continue;
                    }

                    $sql .= $line;

                    // اكتشاف نهاية الاستعلام
                    if (preg_match('/;\s*$/', $line)) {
                        // تحويل syntax MySQL إلى PostgreSQL
                        $processedSql = preg_replace([
                            '/\bLOCK TABLES\b.*?;/is',
                            '/\bUNLOCK TABLES\b/i',
                            '/\bAUTO_INCREMENT\b/i',
                            '/ENGINE=InnoDB/i',
                            '/`/'
                        ], [
                            '',
                            '',
                            'SERIAL PRIMARY KEY',
                            '',
                            '"'
                        ], $sql);

                        // إصلاح خاصية IF EXISTS في PostgreSQL
                        $processedSql = str_replace('IF EXISTS', '', $processedSql);
                        $processedSql = str_replace('DROP TABLE', 'DROP TABLE IF EXISTS', $processedSql);

                        // تجاهل الاستعلامات الفارغة
                        if (empty(trim($processedSql))) {
                            $sql = '';
                            continue;
                        }

                        try {
                            DB::connection('pgsql')->unprepared($processedSql);
                            $queryCount++;
                        } catch (\Exception $e) {
                            fclose($fp);
                            return back()->with('error', '❌ فشل في الاستعلام #' . $queryCount . ': ' . $e->getMessage());
                        }

                        $sql = '';
                    }
                }

                fclose($fp);
                return back()->with('success', '✅ تم تنفيذ ' . $queryCount . ' استعلام بنجاح!');
            } catch (\Exception $e) {
                return back()->with('error', '❌ خطأ غير متوقع: ' . $e->getMessage());
            }
        }
        return back()->with('error', '❌ لم يتم العثور على الملف!');
    }
}
