<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyEntrie extends Model
{
    use HasFactory;


    protected $table = 'daily_entries';

    // المفتاح الأساسي
    protected $primaryKey = 'entrie_id';

    // الحقول القابلة للتعبئة
    protected $fillable = [
        'Amount_debit',
        'account_debit_id',
        'Amount_Credit',
        'account_Credit_id',
        'Statement',
        'Daily_page_id',
        'Currency_name',
        'User_id',
    ];

    // العلاقات مع الجداول الأخرى

    // علاقة مع جدول sub_accounts - الحساب المدين
    public function debitAccount()
    {
        return $this->belongsTo(SubAccount::class, 'account_debit_id', 'sub_account_id');
    }

    // علاقة مع جدول sub_accounts - الحساب الدائن
    public function creditAccount()
    {
        return $this->belongsTo(SubAccount::class, 'account_Credit_id', 'sub_account_id');
    }
    public function subAccount()
    {
        return $this->belongsTo(SubAccount::class, 'sub_account_id');
    }

    
    // علاقة belongsTo مع الحساب الفرعي كمدين
    public function debitSubAccount()
    {
        return $this->belongsTo(SubAccount::class, 'account_debit_id');
    }

    // علاقة belongsTo مع الحساب الفرعي كدائن
    public function creditSubAccount()
    {
        return $this->belongsTo(SubAccount::class, 'account_Credit_id');
    }
    // // علاقة مع جدول users
    // public function user()
    // {
    //     return $this->belongsTo(User::class, 'User_id', 'id');
    // }

    // // علاقة مع جدول general_journal
    // public function dailyPage()
    // {
    //     return $this->belongsTo(GeneralJournal::class, 'Daily_page_id', 'page_id');
    // }
}
