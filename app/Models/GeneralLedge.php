<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralLedge extends Model
{
    use HasFactory;
    protected $table = 'general_ledges';

    // الأعمدة التي يمكن تعيينها بشكل جماعي
    protected $fillable = [
        'Account_id',
      'User_id',
      
        'Main_id',
        'accounting_id',
    ];

    // العلاقة مع جدول sub_accounts
    public function subAccount()
    {
        return $this->belongsTo(SubAccount::class, 'Account_id');
    }
}
