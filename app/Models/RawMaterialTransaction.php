<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawMaterialTransaction extends Model
{
    use HasFactory;

    protected $primaryKey = 'transaction_id';
    
    protected $fillable = [
        'production_order_id',
        'material_id',
        'planned_quantity',
        'actual_quantity',
        'returned_quantity',
        'unit_cost',
        'total_cost',
        'warehouse_id',
        'location_id',
        'issued_by',
        'received_by',
        'return_date',
        'notes'
    ];

    protected $casts = [
        'issue_date' => 'datetime',
        'return_date' => 'datetime',
    ];

    // العلاقات
    public function productionOrder()
    {
        return $this->belongsTo(ProductionOrder::class,'production_order_id');
    }

    public function material()
    {
        return $this->belongsTo(Product::class, 'material_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(SubAccount::class,'warehouse_id');
    }

    public function location()
    {
        return $this->belongsTo(SubAccount::class,'location_id');
    }

    public function issuedByUser()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function receivedByUser()
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}