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
            $file = $request->file('database_file');
            $path = $file->getRealPath();
            $sqlContent = file_get_contents($path);

            // 🔹 استبدال جميع القيم الفارغة '' بـ NULL أو 0 في عمود user_id
            $sqlContent = preg_replace("/VALUES\s*\(\s*''\s*,/", "VALUES (NULL,", $sqlContent);
            $sqlContent = preg_replace("/,\s*''\s*\)/", ", NULL)", $sqlContent);
            $sqlContent = preg_replace("/\(\s*''\s*,/", "(NULL,", $sqlContent);
            $sqlContent = preg_replace("/,\s*''\s*,/", ", NULL,", $sqlContent);

            // 🔹 إعدادات قاعدة البيانات
            $host = env('DB_HOST');
            $username = env('DB_USERNAME');
            $password = env('DB_PASSWORD');
            $database = env('DB_DATABASE');
            $port = env('DB_PORT');

            // 🔹 الاتصال بقاعدة البيانات باستخدام pg_connect
            $connectionString = "host={$host} port={$port} dbname={$database} user={$username} password={$password}";
            $pgConnection = pg_connect($connectionString);

            if (!$pgConnection) {
                return back()->with('error', '❌ فشل الاتصال بقاعدة البيانات.');
            }

            // 🔹 تنفيذ جميع استعلامات SQL دفعة واحدة
            $queries = explode(';', $sqlContent); // تقسيم المحتوى إلى استعلامات منفصلة

            foreach ($queries as $query) {
                $query = trim($query);
                if (!empty($query)) {
                    $result = pg_query($pgConnection,
                        $query
                    );
                    if (!$result) {
                        $error = pg_last_error($pgConnection);
                        pg_close($pgConnection);
                        return back()->with('error', '❌ خطأ في تنفيذ SQL: ' . $error);
                    }
                }
            }

            // 🔹 إغلاق الاتصال
            pg_close($pgConnection);

            // 🔹 تشغيل migrations
            Artisan::call('migrate', ['--force' => true]);

            return back()->with('success', '✅ تم استعادة قاعدة البيانات بنجاح!');
        }

        return back()->with('error', '❌ لم يتم العثور على الملف!');
    }
}
