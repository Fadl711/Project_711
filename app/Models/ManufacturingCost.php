<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManufacturingCost extends Model
{
    use HasFactory;

  


    protected $table = 'manufacturing_costs';

    protected $fillable = [
        'production_order_id',
        'cost_type',
        'amount',
        'gl_account_id',
        'cost_date',
        'description',
        'details',
        'created_by'
    ];

    protected $casts = [
        'details' => 'array',
        'cost_date' => 'date',
        'amount' => 'decimal:2'
    ];
    public function dailyEntrie()
    {
        return $this->belongsTo(DailyEntrie::class, 'gl_account_id');
    }

  
   
    

    // علاقة مع أمر الإنتاج
    public function productionOrder()
    {
        return $this->belongsTo(ProductionOrder::class, 'production_order_id');
    }

    // علاقة مع حساب دفتر الأستاذ
    public function glAccount()
    {
        return $this->belongsTo(SubAccount::class, 'gl_account_id');
    }

    // علاقة مع المستخدم الذي أنشأ التكلفة
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // دالة لتحديد أنواع التكاليف المتاحة
    public static function getCostTypes()
    {
        return [
            'material' => 'تكلفة المواد',
            'labor' => 'تكلفة العمالة',
            'overhead' => 'تكاليف صناعية غير مباشرة',
            'energy' => 'تكلفة الطاقة',
            'depreciation' => 'استهلاك المعدات',
            'other' => 'تكاليف أخرى'
        ];
        
    
}
}