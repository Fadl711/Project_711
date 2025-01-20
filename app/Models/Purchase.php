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
    'Quantityprice',
    'Supplier_id',
    'categorie_id',
];
public function invoice()
{
    return $this->belongsTo(PurchaseInvoice::class, 'Purchase_invoice_id', 'purchase_invoice_id');
} 

// العلاقة مع SubAccount (المخزن الوجهة)
public function warehouseTo()
{
    return $this->belongsTo(SubAccount::class, 'warehouse_to_id', 'sub_account_id');
}

// العلاقة مع SubAccount (الحساب المالي)


// العلاقة مع SubAccount (العميل)
public function customer()
{
    return $this->belongsTo(SubAccount::class, 'Supplier_id', 'sub_account_id');
}

// العلاقة مع User
public function user()
{
    return $this->belongsTo(User::class, 'User_id');
}
// في Purchase.php

// تعريف العلاقات (إذا لزم الأمر)

}
