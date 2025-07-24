<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Double_entry extends Model
{
    protected $table = 'double_entries';
    protected $fillable = [
        'account_debit_id',
        'Statement',
        'User_id',
        'account_type',
    ];
    public function double_entries()
    {
        return $this->hasMany(DailyEntrie::class, 'double_entry_id', 'id');
    }
    public function debitAccount()
    {
        return $this->belongsTo(SubAccount::class, 'account_debit_id', 'sub_account_id');
    }

    // علاقة مع جدول sub_accounts - الحساب الدائن
    public function creditAccount()
    {
        return $this->belongsTo(SubAccount::class, 'account_debit_id', 'sub_account_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'User_id', 'id');
    }
}
