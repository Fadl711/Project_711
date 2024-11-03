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
    //    'account_Credit_id',
    //    'account_debit_id',
    //    'Currency_id',
    //    'Store_id',
      
   ];

   // تعريف العلاقة مع المورد (الحساب الفرعي)
   public function supplier()
   {
       return $this->belongsTo(SubAccount::class, 'Supplier_id');
   }

   // تعريف العلاقة مع المستخدم
   public function user()
   {
       return $this->belongsTo(User::class, 'User_id');
   }
}
