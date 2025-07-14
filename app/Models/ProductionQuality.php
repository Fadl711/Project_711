<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionQuality extends Model
{
    use HasFactory;

    protected $fillable = [
        'production_order_id',
        'stage_id',
        'sample_size',
        'defect_count',
        'defect_rate',
        'result',
        'measurements',
        'defect_description',
        'corrective_action',
        'inspector_id',
        'approved_by'
    ];

    protected $casts = [
        'measurements' => 'array'
    ];

    public function productionOrder()
    {
        return $this->belongsTo(ProductionOrder::class, 'production_order_id');
    }

    public function stage()
    {
        return $this->belongsTo(ProductionStage::class, 'stage_id');
    }

    public function inspector()
    {
        return $this->belongsTo(SubAccount::class, 'inspector_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}