<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralLedge extends Model
{
    use HasFactory;
    protected $table = 'general_ledges';
    protected $primaryKey = 'general_ledge_id';

    // الأعمدة التي يمكن تعيينها بشكل جماعي
    protected $fillable = [
        'Account_id',
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
    public function subAccount()
    {
        return $this->belongsTo(SubAccount::class, 'Account_id');
    }
}
