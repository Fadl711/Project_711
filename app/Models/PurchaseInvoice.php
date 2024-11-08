<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseInvoice extends Model
{
    use HasFactory;
   // اسم الجدول
   protected $table = 'purchase_invoices';

   // الحقول القابلة للتعبئة
   protected $fillable = [
       'Receipt_number',
       'Total_invoice',
       'Total_cost',
       'Paid',
       'User_id',
       'Invoice_type',
       'Supplier_id',
       'accounting_period_id',
        'transaction_type',
    //    'account_debit_id',
    //    'Currency_id',
    //    'Store_id',
      
   ];

   // تعريف العلاقة مع المورد (الحساب الفرعي)
   public function supplier()
   {
       return $this->belongsTo(SubAccount::class, 'sub_account_id','Supplier_id');
   }

   // تعريف العلاقة مع المستخدم
   public function user()
   {
       return $this->belongsTo(User::class, 'User_id');
   }
   // في نموذج PurchaseInvoice
public function purchases()
{
    return $this->hasMany(Purchase::class, 'purchase_invoice_id', 'purchase_invoice_id');
}
public function subAccount()
{
    return $this->belongsTo(SubAccount::class, 'sub_account_id');
}

}
