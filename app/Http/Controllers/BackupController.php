<?php

namespace App\Http\Controllers;

use App\Helpers\InternetHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    public function showForm()
    {
        return view('backup');
    }

    public function createBackup()
    {

        if (app()->environment('local')) {
            $database = env('DB_DATABASE');
            $username = env('DB_USERNAME');
            $password = env('DB_PASSWORD');
            $backupPath = storage_path('app/backups/');
            $filename = 'backup-' . auth()->user()->name  . Carbon::now()->format('Y-m-d_H-i-s') . '.sql';

            // تأكد من أن المجلد موجود
            if (!File::exists($backupPath)) {
                File::makeDirectory($backupPath, 0755, true);
            }

            // استخدام mysqldump لإنشاء النسخة الاحتياطية
            $command = "mysqldump --user={$username} --password={$password} --host=localhost {$database} > {$backupPath}{$filename}";
            exec($command, $output);

            // التحقق من نجاح النسخ الاحتياطي

            // الحصول على أحدث ملف تم إنشاؤه
            $files = glob($backupPath . '/*.sql');
            if (empty($files)) {
                return response()->json(['error' => 'فشل إنشاء النسخة الاحتياطية.'], 500);
            }

            // أحدث ملف
            $latestFile = end($files);

            // اسم الملف مع التاريخ

            // إرجاع الملف للتنزيل
            return Response::download($latestFile, $filename)->deleteFileAfterSend(true);
        } else {
            $database = env('DB_DATABASE');
            $username = env('DB_USERNAME');
            $password = env('DB_PASSWORD');
            $host = env('DB_HOST');
            $port = env('DB_PORT');
 
            // اسم الملف
            $filename = 'backup-'.auth()->user()->name . Carbon::now()->format('Y-m-d_H-i-s') . '.sql';
            // مسار مؤقت على الخادم لحفظ النسخة الاحتياطية قبل الرفع
            $tempBackupPath = '/tmp/' . $filename; // يتم استخدام مجلد /tmp على الخادم

            // استخدام pg_dump لإنشاء النسخة الاحتياطية
            $command = "PGPASSWORD='{$password}' pg_dump -U {$username} -h {$host} -p {$port} -d {$database} > {$tempBackupPath}";
            exec($command, $output, $returnVar);

            // التحقق من نجاح الأمر
            if ($returnVar !== 0 || !File::exists($tempBackupPath)) {
                return back()->with(['error' => 'فشل إنشاء النسخة الاحتياطية.']);
            }

            // رفع الملف إلى Disk R2
            Storage::disk('r2')->put('backups/' . $filename, file_get_contents($tempBackupPath));

            // التحقق من أن الملف تم رفعه بنجاح
            if (!Storage::disk('r2')->exists('backups/' . $filename)) {
                return back()->with(['error' => 'فشل رفع النسخة الاحتياطية إلى R2.']);
            }

            // حذف الملف المؤقت بعد الرفع
            File::delete($tempBackupPath);

            // رابط التنزيل من Disk R2
            $downloadUrl = Storage::disk('r2')->url('backups/' . $filename);

            // إعادة توجيه المستخدم إلى رابط التنزيل
            return redirect($downloadUrl);
        }



    }

    public function backupDatabase(Request $request)

    {

        // التحقق مما إذا كان هناك access token في الجلسة
        logger('Backup Session Check:', [
            'token' => $request->session()->get('google_access_token'),
            'session_id' => $request->session()->getId()
        ]);

        if (!$request->session()->has('google_access_token')) {
            logger('Missing Google Token in Backup');
            return redirect()->route('google.login');
        }

        $database = env('DB_DATABASE');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $backupPath = storage_path('app/backups/');
        $filename = 'backup-' . Carbon::now()->format('Y-m-d_H-i-s') . '.sql';

        // تأكد من أن المجلد موجود
        if (!File::exists($backupPath)) {
            File::makeDirectory($backupPath, 0755, true);
        }

        // استخدام mysqldump لإنشاء النسخة الاحتياطية
        $command = "mysqldump --user={$username} --password={$password} --host=localhost {$database} > {$backupPath}{$filename}";
        exec($command, $output, $returnVar);

        // التحقق من نجاح النسخ الاحتياطي
        if ($returnVar !== 0 || !File::exists($backupPath . $filename) || filesize($backupPath . $filename) == 0) {
            return response()->json(['message' => 'Backup failed or empty'], 500);
        }

        // رفع النسخة الاحتياطية إلى Google Drive
        $client = new \Google_Client();
        $client->setAccessToken(session('google_access_token'));

        $service = new \Google_Service_Drive($client);
        $file = new \Google_Service_Drive_DriveFile();
        $file->setName($filename);
        $file->setParents([env('GOOGLE_DRIVE_FOLDER_ID')]);

        $content = File::get($backupPath . $filename);
        $result = $service->files->create($file, [
            'data' => $content,
            'mimeType' => 'application/sql',
            'uploadType' => 'multipart'
        ]);

        // حذف الملف المحلي بعد رفعه
        File::delete($backupPath . $filename);

        return to_route('backup.form')->with(['success' => 'تم إنشاء النسخة الاحتياطية بنجاح.']);
    }
}
