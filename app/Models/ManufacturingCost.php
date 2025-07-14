<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManufacturingCost extends Model
{
    use HasFactory;

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
        'cost_date' => 'date'
    ];

    public function productionOrder()
    {
        return $this->belongsTo(ProductionOrder::class, 'production_order_id');
    }

    public function dailyEntrie()
    {
        return $this->belongsTo(DailyEntrie::class, 'gl_account_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}