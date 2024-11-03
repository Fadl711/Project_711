<?php

namespace App\Http\Controllers;

use App\Enum\AccountClass;
use App\Models\AccountingPeriod;
use Carbon\Carbon;
use GuzzleHttp\Promise\Create;
use Illuminate\Container\Attributes\DB;
use Illuminate\Http\Request;

class HomeCoctroller extends Controller
{

    public function index()
    {
        // التحقق من عدد الفترات المحاسبية الموجودة
        if (AccountingPeriod::count() == 0 || !AccountingPeriod::where('is_closed', false)->exists()) {
            // إنشاء فترة محاسبية جديدة
            AccountingPeriod::create([
                'Year' => now()->year,          // إدخال السنة كرقم
                'Month' => now()->month,        // إدخال الشهر كرقم
                'Today' => now()->format('Y-m-d'), // إدخال التاريخ الكامل
                'start_date' => now()->format('Y-m-d'), // إدخال تاريخ البداية
                'is_closed' => false,           // تحديد أن الفترة غير مغلقة
            ]);
        }
    
        // عرض الصفحة المطلوبة
        return view('home.index');
    }
    
}
