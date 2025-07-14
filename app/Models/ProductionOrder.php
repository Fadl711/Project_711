<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductionOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number',
        'product_id',
        'line_id',
        'planned_quantity',
        'produced_quantity',
        'approved_quantity',
        'start_date',
        'end_date',
        'actual_start',
        'actual_end',
        'status',
        'priority',
        'estimated_cost',
        'actual_cost',
        'sales_order_id',
        'created_by',
        'approved_by',
        'notes',
        'cancellation_reason'
    ];

    protected $dates = ['start_date', 'end_date', 'actual_start', 'actual_end'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function line()
    {
        return $this->belongsTo(ProductionLine::class, 'line_id');
    }

    public function stages()
    {
        return $this->belongsToMany(ProductionStage::class, 'production_order_stages')
            ->withPivot(['status', 'start_time', 'end_time', 'notes']);
    }

    public function inventoryTransactions()
    {
        return $this->hasMany(InventoryTransaction::class, 'production_order_id');
    }

    public function manufacturingCosts()
    {
        return $this->hasMany(ManufacturingCost::class, 'production_order_id');
    }

    public function qualityChecks()
    {
        return $this->hasMany(ProductionQuality::class, 'production_order_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
      // دوال مساعدة
    public static function getStatuses()
    {
        return [
            'draft' => 'مسودة',
            'planned' => 'مخطط',
            'in_progress' => 'قيد التنفيذ',
            'paused' => 'متوقف',
            'completed' => 'مكتمل',
            'canceled' => 'ملغى'
        ];
    }

    public static function getPriorities()
    {
        return [
            'low' => 'منخفض',
            'medium' => 'متوسط',
            'high' => 'عالي',
            'urgent' => 'عاجل'
        ];
    }

    public function getStatusClass()
    {
        $classes = [
            'draft' => 'bg-gray-400 text-gray-800',
            'planned' => 'bg-blue-200 text-blue-800',
            'in_progress' => 'bg-yellow-200 text-yellow-800',
            'paused' => 'bg-orange-200 text-orange-800',
            'completed' => 'bg-green-200 text-green-800',
            'canceled' => 'bg-red-200 text-red-800'
        ];

        return $classes[$this->status] ?? 'bg-gray-200 text-gray-800';
    }
  public function canBeModified()
    {
        return !in_array($this->status, ['completed', 'canceled']);
    }

}