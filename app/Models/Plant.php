<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'location',
        'area',
        'establishment_date',
        'status',
        'employee_count',
        'annual_production_capacity',
        'description',
        'facilities',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'establishment_date' => 'date',
        'area' => 'decimal:2',
        'annual_production_capacity' => 'decimal:2',
        'facilities' => 'array'
    ];

    protected $attributes = [
        'status' => 'active',
        'employee_count' => 0
    ];

    // العلاقة مع الأقسام
    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    // العلاقة مع خطوط الإنتاج
    public function productionLines()
    {
        return $this->hasMany(ProductionLine::class);
    }

    // العلاقة مع المستخدم الذي أنشأ السجل
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // العلاقة مع المستخدم الذي قام آخر تعديل
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // نطاقات البحث (Scopes)
    
    /**
     * نطاق للمصانع النشطة فقط
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * نطاق للمصانع تحت الإنشاء
     */
    public function scopeUnderConstruction($query)
    {
        return $query->where('status', 'under_construction');
    }

    /**
     * الحصول على حالة المصنع كاسم كامل
     */
    public function getStatusNameAttribute()
    {
        $statuses = [
            'active' => 'نشط',
            'inactive' => 'غير نشط',
            'under_construction' => 'قيد الإنشاء'
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    /**
     * التحقق مما إذا كان المصنع قيد الإنشاء
     */
    public function isUnderConstruction()
    {
        return $this->status === 'under_construction';
    }

    /**
     * حساب الكثافة العمالية (موظفين لكل متر مربع)
     */
    public function getEmployeeDensityAttribute()
    {
        if ($this->area == 0) {
            return 0;
        }

        return round($this->employee_count / $this->area, 2);
    }
}