<?php

namespace App\Models;

use App\Enum\AccountType;
use App\Enum\Deportatton;
use App\Enum\IntOrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainAccount extends Model
{
    use HasFactory;
    protected $fillable = [
        'main_account_id',
        'Nature_account',
         'account_name', 
         'typeAccount',
         'User_id',
         'Type_migration',
        ];
        
 
        protected $casts = [
            'typeAccount' => AccountType::class, // تحويل الحقل إلى Enum
        ];
        protected $casts1 = [
            'typeAccount' => Deportatton::class, // تحويل الحقل إلى Enum
        ];

        
        public function subAccounts()
        {
            return $this->hasMany(SubAccount::class, 'Main_id');
        }

}
