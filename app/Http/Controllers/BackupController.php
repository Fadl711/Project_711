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
        try {
            $database = env('DB_DATABASE');
            $username = env('DB_USERNAME');
            $password = env('DB_PASSWORD');
            $filename = 'backup-' . auth()->user()->name . '-' . Carbon::now()->format('Y-m-d_H-i-s') . '.sql';
            $backupPath = storage_path('app/backups/');
    
            // Ensure backup directory exists
            if (!File::exists($backupPath)) {
                File::makeDirectory($backupPath, 0755, true);
            }
    
            if (app()->environment('local')) {
                // MySQL Backup for local environment
                $command = "mysqldump --user={$username} --password={$password} --host=localhost {$database} > {$backupPath}{$filename} 2>&1";
                exec($command, $output, $returnVar);
    
                if ($returnVar !== 0) {
                    \Log::error('MySQL Backup Failed', ['output' => $output]);
                    return response()->json(['error' => 'فشل إنشاء النسخة الاحتياطية: ' . implode("\n", $output)], 500);
                }
    
                if (!File::exists($backupPath . $filename)) {
                    return response()->json(['error' => 'تم تنفيذ الأمر ولكن الملف غير موجود'], 500);
                }
    
                return Response::download($backupPath . $filename, $filename)->deleteFileAfterSend(true);
                
            } else {
                // PostgreSQL Backup for production
                $host = env('DB_HOST');
                $port = env('DB_PORT');
                $tempBackupPath = $backupPath . $filename;
    
                // First check if pg_dump exists
                exec('which pg_dump', $whichOutput, $whichReturn);
                if ($whichReturn !== 0) {
                    return back()->with(['error' => 'أداة pg_dump غير مثبتة على الخادم']);
                }
    
                $command = "PGPASSWORD='{$password}' pg_dump -U {$username} -h {$host} -p {$port} -d {$database} -f {$tempBackupPath} 2>&1";
                exec($command, $output, $returnVar);
    
                if ($returnVar !== 0) {
                    \Log::error('PostgreSQL Backup Failed', ['output' => $output]);
                    return back()->with(['error' => 'فشل إنشاء النسخة الاحتياطية: ' . implode("\n", $output)]);
                }
    
                if (!File::exists($tempBackupPath)) {
                    return back()->with(['error' => 'تم تنفيذ الأمر ولكن الملف غير موجود']);
                }
    
                // Upload to R2
                try {
                    Storage::disk('r2')->put('backups/' . $filename, file_get_contents($tempBackupPath));
                    
                    if (!Storage::disk('r2')->exists('backups/' . $filename)) {
                        throw new \Exception('فشل التحقق من وجود الملف بعد الرفع');
                    }
    
                    File::delete($tempBackupPath);
    
                    $downloadUrl = Storage::disk('r2')->url('backups/' . $filename);
                    return redirect($downloadUrl);
    
                } catch (\Exception $e) {
                    \Log::error('R2 Upload Failed', ['error' => $e->getMessage()]);
                    return back()->with(['error' => 'فشل رفع النسخة الاحتياطية: ' . $e->getMessage()]);
                }
            }
    
        } catch (\Exception $e) {
            \Log::error('Backup Process Failed', ['error' => $e->getMessage()]);
            return app()->environment('local') 
                ? response()->json(['error' => 'خطأ غير متوقع: ' . $e->getMessage()], 500)
                : back()->with(['error' => 'خطأ غير متوقع: ' . $e->getMessage()]);
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
