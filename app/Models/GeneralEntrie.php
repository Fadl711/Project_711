<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralEntrie extends Model
{
    use HasFactory;
    protected $table = 'general_entries';

    // تحديد المفتاح الأساسي
    protected $primaryKey = 'id';

    // إذا كان المفتاح الأساسي غير متزايد تلقائيًا، يمكنك تحديد هذا:
    // public $incrementing = false;

    // تحديد نوع المفتاح الأساسي

    // تحديد الحقول التي يمكن ملؤها (Mass Assignable)
    protected $fillable = [
        'sub_id',
        'main_id',
        'daily_entry_id',
        'daily_Page_id',
        'user_id',
        'general_ledger_page_number_id',
        'accounting_period_id',
        'entry_type',
        'amount',
        'currency',
        'exchange_rate',
        'description',
        'entry_date',
        'status',
        'type_account',
        'invoice_type',
        'invoice_id',
        'currency_name',
    ];
    public function generalEntries() {
        return $this->belongsTo(SubAccount::class, 'sub_id'); // أو حسب ما يناسب
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'User_id','id');
    }
    public function debitAccount()
    {
        return $this->belongsTo(SubAccount::class, 'sub_id', 'sub_account_id');
    }
    public function subAccount()
    {
        return $this->belongsTo(SubAccount::class, 'sub_id', 'sub_account_id');
    }
    public function Daily_entryId()
    {
        return $this->belongsTo(DailyEntrie::class, 'daily_entry_id', 'entrie_id');
    }
    public function DailyEntry()
    {
        return $this->belongsTo(DailyEntrie::class, 'daily_entry_id');
    }
    public function getTransactionTypeLabelAttribute()
    {
        return $this->transactionType->label() ?? 'غير محدد';
    }
  

}
