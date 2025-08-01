<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccountingPeriod extends Model
{
    use HasFactory;

    protected $table = 'accounting_periods';
    protected $primaryKey = 'accounting_period_id';

    protected $fillable = [
        'Year',
        'Month',
       'Today',
       'start_date', // تاريخ البداية
       'end_date',   // تاريخ النهاية
       'is_closed',   // حالة الفترة
    ];
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('Y-m-d');
    }
   // app/Models/AccountingPeriod.php

public function getMonthsInPeriod(): array
{
    $start = Carbon::parse($this->start_date);
    $end = Carbon::parse($this->end_date);
    $today = Carbon::today();
    
    $months = [];
    $currentMonth = $start->copy()->startOfMonth();
    
    while ($currentMonth <= $end) {
        $monthEnd = $currentMonth->copy()->endOfMonth();
        
        // تحديد حالة الشهر
        $isPassed = $today > $monthEnd;
        $isCurrent = $today->between($currentMonth, $monthEnd);
        $isUpcoming = $today < $currentMonth;
        
        // إذا كانت الفترة تنتهي قبل نهاية الشهر
        if ($monthEnd > $end) {
            $monthEnd = $end;
        }
        
       $months[] = [
        'name' => $currentMonth->locale('ar')->translatedFormat('F Y'),
        'start_date' => $currentMonth->format('Y-m-d'),
        'end_date' => $monthEnd->format('Y-m-d'),
        'is_passed' => $isPassed,
        'is_current' => $isCurrent,
        'is_upcoming' => $isUpcoming,
        'days_count' => $currentMonth->diffInDays($monthEnd) + 1,
        'month_number' => $currentMonth->month, // إضافة رقم الشهر (1-12)
        'year' => $currentMonth->year // إضافة السنة إن احتجتها
    ];
        
        $currentMonth->addMonth()->startOfMonth();
    }
    
    return $months;
}

}
