<?php

namespace App\Models;

use App\Enum\AccountClass;
use App\Enum\AccountType;
use App\Enum\Deportatton;
use App\Enum\IntOrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainAccount extends Model
{
    use HasFactory;
       protected $table = 'main_accounts';
        protected $primaryKey = 'main_account_id';
    protected $fillable = [
        'main_account_id',
        'Nature_account',
         'account_name',
         'typeAccount',
         'User_id',
         'Type_migration',
         'AccountClass',

        ];
     

        public function subAccounts()
        {
            return $this->hasMany(SubAccount::class, 'main_id', 'main_account_id');
        }
        public function user()
        {
            return $this->belongsTo(User::class, 'User_id', 'id');
        }


        // دالة لإرجاع التسمية بناءً على قيمة account_class
        public function accountClassLabel()
        {
            // استخدام enum AccountClass
            return AccountClass::from($this->AccountClass)->label();
        }
    public function accountTypeLabel()
    {
        return AccountType::from($this->typeAccount)->label();
    }









}
