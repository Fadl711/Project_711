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
          'total_price',
           'total_price_sale',
        'User_id',
         'paid_amount', 
         
         
         'remaining_amount', 
         'payment_type',
        'currency_id',
         'exchange_rate', 
         
         'transaction_type',
         'account_id',
        'shipping_bearer',
        'shipping_amount',
         'accounting_period_id',
          'discount', 
        'net_total_after_discount'
    ];
  

    // عند حفظ الفاتورة، نقوم بحساب الإجمالي الصافي بعد الخصم
    public static function boot()
    {
        parent::boot();

        // عند حفظ الفاتورة، يتم حساب الإجمالي الصافي بعد الخصم
        static::saving(function ($invoice) {
            // حساب الإجمالي الصافي بعد الخصم
            $invoice->net_total_after_discount = $invoice->total_price_sale - $invoice->discount;
        });
    }

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

    public function getUserNameAttribute()
    {
        return $this->user->name ?? 'غير معروف';
    }
  
    // تنسيق تاريخ الإنشاء
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('Y-m-d');
    }
  
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'currency_id');
    }

}
