<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductBom extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'material_id',
        'quantity',
        'unit_id',
        'waste_factor',
        'default_warehouse_id',
        'standard_cost',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'quantity' => 'decimal:3',
        'waste_factor' => 'decimal:2',
        'standard_cost' => 'decimal:5',
    ];

   

     public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function material()
    {
        return $this->belongsTo(Product::class, 'material_id', 'product_id');
    }

    public function unit()
    {
        return $this->belongsTo(Category::class, 'unit_id', 'categorie_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(SubAccount::class, 'default_warehouse_id', 'sub_account_id');
    }

  
}