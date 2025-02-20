<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubAccount extends Model
{
    use HasFactory;

protected $table = 'sub_accounts';
protected $primaryKey = 'sub_account_id';
    protected $fillable = [
        'sub_name',
        'Main_id',
        'debtor_amount' ,
        'creditor_amount',
        'name_The_known' ,
        'Known_phone' ,
        'User_id',
        'Phone',
        'typeAccount',
        'AccountClass',

    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'User_id', 'id');
    }
    public function mainAccount()
    {
        return $this->belongsTo(MainAccount::class, 'Main_id', 'main_account_id');
    }


    public function daily_entries()
{
    return $this->hasMany(DailyEntrie::class, 'sub_account_id');
}

public function purchases()
{
    return $this->belongsTo(Purchase::class, 'Supplier_id', 'sub_account_id');
}
public function purchase_invoices()
{
    return $this->belongsTo(PurchaseInvoice::class, 'Supplier_id', 'sub_account_id');
}

public function getMainAccountClassLabelAttribute()
{
    return $this->mainAccount->accountClassLabel() ?? 'غير معروف';
}

// علاقة belongsTo بين الحساب الفرعي والحساب الرئيسي


// علاقة hasMany بين الحساب الفرعي وقيود اليومية كمدين
public function dailyEntriesDebit()
{
    return $this->hasMany(DailyEntrie::class, 'account_debit_id');
}

// علاقة hasMany بين الحساب الفرعي وقيود اليومية كدائن
public function dailyEntriesCredit()
{
    return $this->hasMany(DailyEntrie::class, 'account_Credit_id', 'sub_account_id');
}
public function dailyEntries()
{
    return $this->hasMany(DailyEntrie::class, 'account_Credit_id', 'sub_account_id');
}


public function debitEntries()
    {
        return $this->hasMany(DailyEntrie::class, 'account_debit_id', 'sub_account_id');
    }

    public function creditEntries()
    {
        return $this->hasMany(DailyEntrie::class, 'account_Credit_id', 'sub_account_id');
    }

}
