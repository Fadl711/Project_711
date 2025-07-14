<?php

namespace App\Models;

use Google\Service\ShoppingContent\ProductStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductionLine extends Model
{
   use HasFactory, SoftDeletes;

    /**
     * الحقول التي يمكن تعبئتها بشكل جماعي (Mass Assignment)
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'name',
        'description',
        'department_id',
        'plant_id',
        'automation_level',
        'design_capacity',
        'current_capacity',
        'status',
        'commissioning_date',
        'last_calibration_date',
        'hourly_operating_cost',
        'energy_consumption',
        'specifications',
        'safety_requirements',
        'created_by',
        'updated_by'
     
    ];

    /**
     * الحقول التي يجب أن تكون من نوع تاريخ
     *
     * @var array
     */
    protected $dates = [
        'commissioning_date',
        'last_calibration_date',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * الحقول التي يجب تحويلها إلى أنواع معينة
     *
     * @var array
     */
    protected $casts = [
        'specifications' => 'array',
        'safety_requirements' => 'array',
        'design_capacity' => 'decimal:2',
        'current_capacity' => 'decimal:2',
        'hourly_operating_cost' => 'decimal:2',
        'energy_consumption' => 'decimal:2'
    ];

    /**
     * القيم الافتراضية لسمات النموذج
     *
     * @var array
     */
    protected $attributes = [
        'status' => 'active'
    ];

    /**
     * العلاقة مع جدول الأقسام (departments)
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function equipments()
    {
        return $this->belongsTo(SubAccount::class);
    }
    // public function stages()
    // {
    //     return $this->hasMany(ProductionStage::class, 'line_id')->orderBy('sequence');
    // }

    /**
     * العلاقة مع جدول المصانع (plants)
     */
   

     public function plant()
    {
        return $this->belongsTo(Plant::class, 'plant_id');
    }

    public function stages()
    {
        return $this->hasMany(ProductionStage::class, 'line_id');
    }

    public function efficiencies()
    {
        return $this->hasMany(LineEfficiency::class, 'line_id');
    }

    public function maintenances()
    {
        return $this->hasMany(EquipmentMaintenance::class, 'line_id');
    }

    public function productionOrders()
    {
        return $this->hasMany(ProductionOrder::class, 'line_id');
    }
    /**
     * العلاقة مع المستخدم الذي أنشأ السجل
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * العلاقة مع المستخدم الذي قام آخر تعديل
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * نطاقات البحث (Scopes) للمساعدة في استعلامات شائعة
     */

    /**
     * نطاق للخطوط النشطة فقط
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * نطاق للخطوط ذات السعة الفعلية أكبر من قيمة معينة
     */
    public function scopeCapacityGreaterThan($query, $capacity)
    {
        return $query->where('current_capacity', '>', $capacity);
    }

    /**
     * نطاق للخطوط حسب مستوى الأتمتة
     */
    public function scopeByAutomationLevel($query, $level)
    {
        return $query->where('automation_level', $level);
    }

    /**
     * الحصول على مستوى الأتمتة كاسم كامل
     */
    public function getAutomationLevelNameAttribute()
    {
        $levels = [
            'manual' => 'يدوي',
            'semi-auto' => 'شبه آلي',
            'full-auto' => 'كامل الآلية'
        ];

        return $levels[$this->automation_level] ?? $this->automation_level;
    }

    /**
     * الحصول على حالة الخط كاسم كامل
     */
    public function getStatusNameAttribute()
    {
        $statuses = [
            'active' => 'نشط',
            'inactive' => 'غير نشط',
            'maintenance' => 'صيانة',
            'retired' => 'متقاعد'
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    /**
     * التحقق مما إذا كان الخط يحتاج إلى معايرة
     * (افترضنا أن المعايرة يجب أن تكون كل سنة)
     */
    public function needsCalibration()
    {
        if (!$this->last_calibration_date) {
            return true;
        }

        return $this->last_calibration_date->diffInYears(now()) >= 1;
    }

    /**
     * حساب معدل استخدام السعة (النسبة المئوية)
     */
    public function getCapacityUtilizationAttribute()
    {
        if ($this->design_capacity == 0) {
            return 0;
        }

        return round(($this->current_capacity / $this->design_capacity) * 100, 2);
    }
}
