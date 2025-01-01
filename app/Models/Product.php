<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $primaryKey = 'product_id';

    protected $fillable =[
            'Barcode',
            'product_name',
            'Quantity',
            'Purchase_price',
            'Selling_price',
            'Total',
            'Cost',
            'Regular_discount',
            'Special_discount',
            'Profit',
            'note',
            'Categorie_id',
            'currency_id',
            'warehouse_id',
            'User_id',
            'supplier_id',
    ];
     // تحويل Purchase_price إلى أرقام إنجليزية
}
