<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionStage extends Model
{
        use HasFactory;

    /**
     * الحقول التي يمكن تعبئتها بشكل جماعي (Mass Assignment)
     *
     * @var array
     */
    protected $fillable = [
        'line_id',
        'name',
        'sequence',
        'purpose',
        'standard_duration',
        'target_yield',
        'max_defect_rate',
        'required_equipment',
        'equipment_settings',
        'quality_parameters',
        'inspection_instructions',
        'is_active'
    ];

    /**
     * الحقول التي يجب تحويلها إلى أنواع معينة
     *
     * @var array
     */
    protected $casts = [
        'standard_duration' => 'decimal:2',
        'target_yield' => 'decimal:2',
        'max_defect_rate' => 'decimal:2',
        'equipment_settings' => 'array',
        'quality_parameters' => 'array',
        'is_active' => 'boolean'
    ];

    /**
     * القيم الافتراضية لسمات النموذج
     *
     * @var array
     */
    protected $attributes = [
        'target_yield' => 100,
        'is_active' => true
    ];

    /**
     * العلاقة مع خط الإنتاج
     */
    public function productionLine()
    {
        return $this->belongsTo(ProductionLine::class, 'line_id');
        
    }
    public function stages()
{
    return $this->hasMany(ProductionStage::class, 'line_id')->orderBy('sequence');
}

public function activeStages()
{
    return $this->stages()->active();
}

    public function productionOrders()
    {
        return $this->belongsToMany(ProductionOrder::class, 'production_order_stages')
            ->withPivot(['status', 'start_time', 'end_time', 'notes']);
    }

    public function qualityChecks()
    {
        return $this->hasMany(ProductionQuality::class, 'stage_id');
    }
    /**
     * نطاقات البحث (Scopes) للمساعدة في استعلامات شائعة
     */

    /**
     * نطاق للمراحل النشطة فقط
     */


    /**
     * نطاق للمراحل مرتبة حسب التسلسل
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sequence');
    }

    /**
     * نطاق للمراحل التابعة لخط إنتاج معين
     */
  

    /**
     * الحصول على حالة المرحلة كنص
     */
    public function getStatusAttribute()
    {
        return $this->is_active ? 'نشط' : 'غير نشط';
    }

    /**
     * الحصول على المدة المعيارية بشكل منسق
     */
    public function getFormattedDurationAttribute()
    {
        if ($this->standard_duration >= 60) {
            $minutes = floor($this->standard_duration / 60);
            $seconds = $this->standard_duration % 60;
            return "{$minutes} دقيقة {$seconds} ثانية";
        }
        return "{$this->standard_duration} ثانية";
    }

    /**
     * التحقق مما إذا كانت نسبة العيوب مقبولة
     */
    public function isDefectRateAcceptable($actualDefectRate)
    {
        if (is_null($this->max_defect_rate)) {
            return true;
        }
        return $actualDefectRate <= $this->max_defect_rate;
    }

    /**
     * الحصول على إعدادات المعدات كمصفوفة
     */
    public function getEquipmentSettingsArray()
    {
        return $this->equipment_settings ?? [];
    }

    /**
     * الحصول على معايير الجودة كمصفوفة
     */
    public function getQualityParametersArray()
    {
        return $this->quality_parameters ?? [];
    }
     public static function getValidationRules($stageId = null): array
    {
        return [
            'line_id' => 'required|exists:production_lines,id',
            'name' => 'required|string|max:100',
            'sequence' => 'required|integer|min:1',
            'purpose' => 'nullable|string',
            'standard_duration' => 'required|numeric|min:0',
            'target_yield' => 'required|numeric|between:0,100',
            'max_defect_rate' => 'required|numeric|between:0,100',
            'required_equipment' => 'nullable|string|max:255',
            'equipment_settings' => 'nullable|',
            'quality_parameters' => 'nullable|',
            'inspection_instructions' => 'nullable|string',
            'is_active' => 'boolean'
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForLine($query, $lineId)
    {
        return $query->where('line_id', $lineId);
    }

    public function getDurationForQuantity($quantity): float
    {
        return $this->standard_duration * $quantity;
    }

    public function isWithinQualityStandards($defectRate): bool
    {
        return $defectRate <= $this->max_defect_rate;
    }
}