<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
protected static function boot()
{
    parent::boot();
    static::deleting(function($product){
        $product->categories()->delete();
    });
}

    protected $fillable =[
            'barcod',
            'product_name',
            'Categorie_id',
            'Product_price',
            'quantity',
            'Regular_discount',
            'Special_discount',
            'user_id',
            'Currency_id',
            'Total_price',
    ];
}
