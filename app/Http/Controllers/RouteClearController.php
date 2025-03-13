<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class RouteClearController extends Controller
{
    public function clearRoutes()
    {
        // تنفيذ أوامر Artisan
        $results = [];
        $results[] = Artisan::call('cache:clear'); // مسح الكاش
        $results[] = Artisan::call('config:clear'); // مسح الإعدادات
        $results[] = Artisan::call('view:clear'); // مسح العروض
        $results[] = Artisan::call('route:clear'); // مسح المسارات

        // التحقق من نجاح الأوامر
        if (in_array(1, $results)) {
            // إذا فشل أحد الأوامر
            return redirect()->back()->with('error', 'فشل في تحديث المسارات أو أحد الأوامر.');
        } else {
            // إذا نجحت جميع الأوامر
            return redirect()->back()->with('success', 'تم تحديث المسارات والإعدادات بنجاح.');
        }
    }
}
