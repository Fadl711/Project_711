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
     const TRANSACTION_TYPES = [
        'receipt' => 'استلام مواد خام',
        'issue' => 'صرف مواد للإنتاج',
        'return' => 'إرجاع فائض',
        'product_in' => 'إدخال منتج نهائي',
        'waste_out' => 'إخراج مخلفات'
    ];
    protected $casts = [
    'transaction_date' => 'datetime',
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