<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'production_order_id',
        'item_id',
        'quantity',
        'transaction_type',
        'warehouse_id',
        'location_id',
        'unit_cost',
        'total_cost',
        'created_by',
        'notes'
    ];

    public function productionOrder()
    {
        return $this->belongsTo(ProductionOrder::class, 'production_order_id');
    }

    public function item()
    {
        return $this->belongsTo(Product::class, 'item_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(SubAccount::class, 'warehouse_id');
    }

    public function location()
    {
        return $this->belongsTo(SubAccount::class, 'location_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}