<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
