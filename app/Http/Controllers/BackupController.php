<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class BackupController extends Controller
{
    public function showForm()
    {
        return view('backup');
    }

    public function createBackup(Request $request)
    {
        // تنفيذ الأمر لإنشاء النسخة الاحتياطية
        Artisan::call('db:backup');

        // الحصول على أحدث ملف تم إنشاؤه
        $backupPath = storage_path('app/backups');
        $files = glob($backupPath . '/*.sql');
        if (empty($files)) {
            return response()->json(['error' => 'فشل إنشاء النسخة الاحتياطية.'], 500);
        }

        // أحدث ملف
        $latestFile = end($files);

        // اسم الملف مع التاريخ
        $fileName = 'backup_' . Carbon::now()->format('Y_m_d_H_i_s') . '.sql';

        // إرجاع الملف للتنزيل
        return Response::download($latestFile, $fileName)->deleteFileAfterSend(true);
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
