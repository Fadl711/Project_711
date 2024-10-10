<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

protected $primaryKey = 'purchase_id';
    protected $fillable = [
        'purchase_id',
        'Product_name',
         'Barcode',
         'Quantity',
         'Purchase_price',
         'Selling_price',
         'Total',
         'Cost',
         'Currency_id',
         'Supplier_id',
         'User_id',
         'Purchase_invoice_id',
         'Category_id',
         'Store_id',
         'Discount_earned',
         'Profit',
         'Exchange_rate',
         'note',
         'product_id',




        ];

}
