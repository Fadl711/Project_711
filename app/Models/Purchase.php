<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

protected $primaryKey = 'purchase_id';
   
protected $fillable = [
    'Product_name',
    'product_id',
    'Barcode',
    'Purchase_price',
    'Selling_price',
    'Total',
    'Cost',
    'Discount_earned',
    'Profit',
    'Exchange_rate',
    'note',
    'Currency_id',
    'User_id',
    'quantity',
    'Purchase_invoice_id',
    'accounting_period_id',
    'account_id',
    'transaction_type',
    'warehouse_from_id',
    'warehouse_to_id',
    'Supplier_id',
    'categorie_id',
];

// تعريف العلاقات (إذا لزم الأمر)

}
