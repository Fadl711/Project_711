<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralLedgeMain extends Model
{
    use HasFactory;

 // الأعمدة التي يمكن تعيينها بشكل جماعي
    protected $fillable = [
      'User_id',
      'Main_id',
      'accounting_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'User_id');
    }

    public function mainAccount()
    {
        return $this->belongsTo(MainAccount::class, 'Main_id');
    }

   
    // العلاقة مع جدول sub_accounts
  
}
