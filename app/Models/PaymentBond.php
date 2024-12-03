<?php

namespace App\Models;

use App\Enum\PaymentType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentBond extends Model
{
    use HasFactory;
    protected $table = 'payment_bonds';
    protected $primaryKey = 'payment_bond_id';

    protected $fillable = [
        'payment_type',
        'transaction_type',
        'Main_debit_account_id',
        'Debit_sub_account_id',
        'Amount_debit',
        'accounting_period_id',
        'Main_Credit_account_id',
        'Credit_sub_account_id',
        'Statement',
        'Currency_id',
        'User_id',
    ];
    
    // العلاقة مع SubAccount (العملاء)
   
    // العلاقة مع SubAccount
    public function debitSubAccount()
    {
        return $this->belongsTo(SubAccount::class, 'Debit_sub_account_id', 'sub_account_id');
    }

    public function creditSubAccount()
    {
        return $this->belongsTo(SubAccount::class, 'Credit_sub_account_id', 'sub_account_id');
    }

    // العلاقة مع User
    public function user()
    {
        return $this->belongsTo(User::class, 'User_id');
    }

    // العلاقة مع Currency
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'Currency_id', 'currency_id');
    }

    // Accessor: اسم المستخدم
    public function getUserNameAttribute()
    {
        return $this->user->name ?? 'غير معروف';
    }

    // Accessor: تنسيق تاريخ الإنشاء
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('Y-m-d');
    }
}
