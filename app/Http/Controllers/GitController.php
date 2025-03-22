<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class GitController extends Controller
{
    public function gitPull()
    {
        // تنفيذ الأمر git pull
        $gitPath = base_path(); // المسار الجذري للمشروع
        $output = [];
        // الحصول على اسم المستخدم من البيئة
        $username = getenv('USERNAME'); // الحصول على اسم المستخدم

        // تحقق مما إذا كان الدليل موجودًا كدليل آمن
        $safeDirectoryCheck = shell_exec("git config --global --get safe.directory");
        if (strpos($safeDirectoryCheck, $gitPath) === false) {
            // إضافة الدليل كدليل آمن إذا لم يكن موجودًا
            shell_exec("git config --global --add safe.directory " . escapeshellarg($gitPath));
        }

        // تغيير ملكية المجلد
        shell_exec("takeown /f " . escapeshellarg($gitPath) . " /r /d y");
        shell_exec("icacls " . escapeshellarg($gitPath) . " /grant " . escapeshellarg($username) . ":F /t");

        // استخدام cd للانتقال إلى المجلد وتنفيذ git pull
        $output = shell_exec("cd " . escapeshellarg($gitPath) . " && git pull 2>&1");

        Artisan::call('migrate', ['--force' => true]);
        // تحقق من نجاح الأمر
        if ($output === null) {
            return redirect()->back()->with('error', 'فشل في تحديث المشروع: لم يتم الحصول على أي مخرجات.');
        }

        // تحقق من النتائج
        if (strpos($output, 'Already up to date') !== false) {
            return redirect()->back()->with('success', 'المشروع محدث بالفعل.');
        } elseif (strpos($output, 'error') !== false) {
            return redirect()->back()->with('error', 'فشل في تحديث المشروع: ' . nl2br(e($output)));
        } else {
            return redirect()->back()->with('success', 'تم تحديث المشروع بنجاح.');
        }
    }
}
