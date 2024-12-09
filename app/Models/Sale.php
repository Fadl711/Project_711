<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $table = 'sales';
    protected $primaryKey = 'sale_id';

    protected $fillable = [
        'Product_name',
        'product_id',
        'Category_name',
        'accounting_period_id',
        'Barcode',
        'quantity',
        'Selling_price',
        'note',
        'total_amount',
        'net_amount',
        'unit_price',
        'discount_rate',
        'tax_rate',
        'discount',
        'tax',
        'total_price',
        'currency',
        'shipping_cost',
        'financial_account_id',
        'warehouse_to_id',
        'Customer_id',
        'User_id',
        'Invoice_id',
        'Quantityprice',
        'transaction_type',
        'supplier_id',

    ];

   
    // العلاقة مع SalesInvoice
    public function invoice()
    {
        return $this->belongsTo(SaleInvoice::class, 'Invoice_id', 'sales_invoice_id');
    }

    // العلاقة مع SubAccount (المخزن الوجهة)
    public function warehouseTo()
    {
        return $this->belongsTo(SubAccount::class, 'warehouse_to_id', 'sub_account_id');
    }

    // العلاقة مع SubAccount (الحساب المالي)
    public function financialAccount()
    {
        return $this->belongsTo(SubAccount::class, 'financial_account_id', 'sub_account_id');
    }

    // العلاقة مع SubAccount (العميل)
    public function customer()
    {
        return $this->belongsTo(SubAccount::class, 'Customer_id', 'sub_account_id');
    }

    // العلاقة مع User
    public function user()
    {
        return $this->belongsTo(User::class, 'User_id');
    }
}
