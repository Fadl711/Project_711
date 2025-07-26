<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $primaryKey = 'product_id';

    protected $fillable = [
        'Barcode',
        'product_name',
        'Quantity',
        'Purchase_price',
        'Selling_price',
        'Total',
        'Cost',
        'Regular_discount',
        'Special_discount',
        'Profit',
        'note',
        'Categorie_id',
        'currency_id',
        'warehouse_id',
        'User_id',
        'expiry_date',
        'supplier_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
  public function category()
    {
        return $this->belongsTo(Category::class, 'Categorie_id', 'categorie_id');
    }
 public function categories()
    {
        return $this->hasMany(Category::class, 'product_id');
    }
    // يمكن الاحتفاظ بهذا كعلاقة احتياطية إذا لزم الأمر
   

    public function scopeExpiringSoon($query)
    {
        return $query->where('expiry_date', '<=', now()->addMonth())
            ->where('expiry_date', '>', now());
    }
    public function getDaysUntilExpiryAttribute()
    {
        return Carbon::now()->diffInDays(Carbon::parse($this->expiry_date), false);
    }
    public function sales()
    {
        return $this->hasMany(Sale::class, 'product_id');
    }
    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'product_id');
    }

    // تحويل Purchase_price إلى أرقام إنجليزية
}
