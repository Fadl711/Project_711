<?php

namespace App\Models;

use App\Enum\TransactionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseInvoice extends Model
{
    use HasFactory;
   // اسم الجدول
   protected $table = 'purchase_invoices';
   protected $primaryKey = 'purchase_invoice_id';

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
       'Currency_id',
      
   ];
   public function invoicePurchases()
   {
       return $this->hasMany(Purchase::class);
   }
   
//    // تعريف العلاقة مع المورد (الحساب الفرعي)
//    public function supplier()
//    {
//        return $this->belongsTo(SubAccount::class, 'sub_account_id','Supplier_id');
//    }

   // تعريف العلاقة مع المستخدم
//    public function user()
//    {
//        return $this->belongsTo(User::class, 'User_id');
//    }
//    // في نموذج PurchaseInvoice
public function transactionType()
{
    return $this->belongsTo(TransactionType::class, 'transaction_type', 'value');
}
public function purchases()
{
    return $this->hasMany(Purchase::class, 'purchase_invoice_id', 'purchase_invoice_id');
}
public function subAccount()
{
    return $this->belongsTo(SubAccount::class, 'sub_account_id');
}
public function supplier()
{
    return $this->belongsTo(SubAccount::class, 'Supplier_id', 'sub_account_id');
}

public function user()
{
    return $this->belongsTo(User::class, 'User_id', 'id');
}
  // علاقة المستخدم
  public function getUserNameAttribute()
  {
      return $this->user->name ?? 'غير معروف';
  }

  // علاقة نوع الفاتورة
  public function getTransactionTypeLabelAttribute()
  {
      return $this->transactionType->label() ?? 'غير محدد';
  }

  // تنسيق تاريخ الإنشاء
  public function getFormattedDateAttribute()
  {
      return $this->created_at->format('Y-m-d');
  }
}
