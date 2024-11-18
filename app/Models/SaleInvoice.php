<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleInvoice extends Model
{
    use HasFactory;

    protected $table = 'sales_invoices';
    protected $primaryKey = 'sales_invoice_id';

    protected $fillable = [
        'Customer_id',
        'payment_status',
        'shipping_cost',
        'total_price',
        'total_price_sale',
        'User_id',
        'paid_amount',
        'remaining_amount',
        'payment_type',
        'currency_id',
        'exchange_rate',
        'accounting_period_id',
        'shipping_bearer',
    ];

    // العلاقة مع SubAccount (العملاء)
    public function customer()
    {
        return $this->belongsTo(SubAccount::class, 'Customer_id', 'sub_account_id');
    }

    // العلاقة مع User
    public function user()
    {
        return $this->belongsTo(User::class, 'User_id');
    }

    // العلاقة مع Sales
    public function sales()
    {
        return $this->hasMany(Sale::class, 'Invoice_id', 'sales_invoice_id');
    }

}
