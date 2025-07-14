<?php

namespace App\Models;

use Google\Service\Dfareporting\Subaccount;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'plant_id',
        'manager_name',
        'phone',
        'email',
        'type',
        'employee_count',
        'budget',
        'establishment_date',
        'description',
        'equipment',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'establishment_date' => 'date',
        'budget' => 'decimal:2',
        'equipment' => 'array'
    ];

    protected $attributes = [
        'employee_count' => 0
    ];

    // العلاقة مع المصنع
   
    // العلاقة مع خطوط الإنتاج
    public function productionLines()
    {
        return $this->hasMany(ProductionLine::class);
    }
 public function plant()
    {
        return $this->belongsTo(Plant::class, 'plant_id');
    }

    public function employees()
    {
        return $this->hasMany(Subaccount::class, 'department_id');
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
     * نطاق للأقسام حسب النوع
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * نطاق للأقسام الإنتاجية فقط
     */
    public function scopeProduction($query)
    {
        return $this->ofType('production');
    }

    /**
     * الحصول على نوع القسم كاسم كامل
     */
    public function getTypeNameAttribute()
    {
        $types = [
            'production' => 'إنتاج',
            'maintenance' => 'صيانة',
            'quality' => 'جودة',
            'logistics' => 'لوجستيات',
            'admin' => 'إداري'
        ];

        return $types[$this->type] ?? $this->type;
    }

    /**
     * الحصول على الميزانية بشكل منسق
     */
    public function getFormattedBudgetAttribute()
    {
        return number_format($this->budget, 2) . ' ر.س';
    }

    /**
     * التحقق مما إذا كان القسم إنتاجياً
     */
    public function isProduction()
    {
        return $this->type === 'production';
    }
}