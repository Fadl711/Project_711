<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;
       // تحديد اسم الجدول إذا كان مختلفًا عن الاسم الافتراضي
       protected $table = 'inventorys';
       protected $primaryKey = 'id';

       // تحديد الأعمدة القابلة للتعبئة
       protected $fillable = [
           'product_id',
           'quantity',
           'Quantityprice',
           'StoreId',
           'CostPrice',
           'categorie_id',
           'TotalCost',
           'InventoryInvoiceId',
           'InventoryOfficerId',
           'accounting_period_id',
           'User_id',
       ];
   
       // تحديد العلاقات
       public function product()
       {
           return $this->belongsTo(Product::class, 'product_id');
       }
   
       public function inventoryInvoice()
       {
           return $this->belongsTo(InventoryInvoice::class, 'InventoryInvoiceId');
       }
   
       public function user()
       {
           return $this->belongsTo(User::class, 'User_id');
       }
   
       public function inventoryOfficer()
       {
           return $this->belongsTo(User::class, 'InventoryOfficerId');
       }
   
       public function store()
       {
           return $this->belongsTo(SubAccount::class, 'StoreId');
       }
}
