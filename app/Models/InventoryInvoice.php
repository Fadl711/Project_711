<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryInvoice extends Model
{
    use HasFactory;
     // تحديد اسم الجدول إذا كان مختلفًا عن الاسم الافتراضي
     protected $table = 'inventory_invoices';
     protected $primaryKey = 'id';


     // تحديد الأعمدة القابلة للتعبئة
     protected $fillable = [
         'StoreId',
         'InventoryOfficerId',
         'User_id',
         'InventoryTitle',
         'accounting_period_id',
     ];
 
     // تحديد العلاقات
    //  public function store()
    //  {
    //      return $this->belongsTo(SubAccount::class, 'StoreId');
         
    //  }
    //  public function Inventorys()
    //  {
    //      return $this->hasMany(Inventory::class, 'id', 'purchase_invoice_id');
    //  }
    
     public function subAccount()
{
    return $this->belongsTo(SubAccount::class, 'sub_account_id');
}

public function store()
    {
        return $this->belongsTo(SubAccount::class, 'StoreId', 'sub_account_id');
    }
public function employee()
    {
        return $this->belongsTo(User::class, 'InventoryOfficerId', 'id');
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
  public function getSubAccountNameAttribute()
  {
      return $this->employee->name ?? 'غير معروف';
  }
 
     public function inventoryOfficer()
     {
         return $this->belongsTo(User::class, 'InventoryOfficerId');
     }
 
     public function accountingPeriod()
     {
         return $this->belongsTo(AccountingPeriod::class, 'accounting_period_id');
     }
 
     public function inventoryItems()
     {
         return $this->hasMany(Inventory::class, 'InventoryInvoiceId');
     }

}
