<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class BackupController extends Controller
{
    public function showForm()
    {
        return view('backup');
    }

    public function createBackup(Request $request)
    {
        $path = $request->input('path');
        Artisan::call('db:backup', ['path' => $path]);

        return back()->with('success', 'تم إنشاء النسخة الاحتياطية بنجاح.');
    }
}
