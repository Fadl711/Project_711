<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyEntrie extends Model
{
    use HasFactory;
    protected $table = 'daily_entries';
    protected $primaryKey = 'entrie_id';
    // الحقول القابلة للتعبئة
    // الحقول التي يمكن ملؤها
    protected $fillable = [
        'amount_debit',
        'account_debit_id',
        'amount_credit',
        'account_credit_id',
        'statement',
        'daily_page_id',
        'currency_name',
        'user_id',
        'invoice_type',
        'invoice_id',
        'accounting_period_id',
        'status_debit',
        'status',
        'daily_entries_type',
        'exchange_rate',
    ];
    // تحديد أنواع الحقول
    protected $casts = [
        'amount_debit' => 'decimal:2',
        'amount_credit' => 'decimal:2',
        'daily_page_id' => 'integer',
        'user_id' => 'integer',
        'invoice_id' => 'integer',
        'accounting_period_id' => 'integer',
        'account_debit_id' => 'integer',
        'account_credit_id' => 'integer',
        'status_debit' => 'string',
        'status' => 'string',
    ];
    public function subAccounts()
    {
        return $this->belongsTo(SubAccount::class, 'account_debit_id'); // أو حسب ما يناسب
    }

    // علاقة مع جدول sub_accounts - الحساب المدين
    public function debitAccount()
    {
        return $this->belongsTo(SubAccount::class, 'account_debit_id', 'sub_account_id');
    }

    // علاقة مع جدول sub_accounts - الحساب الدائن
    public function creditAccount()
    {
        return $this->belongsTo(SubAccount::class, 'account_credit_id', 'sub_account_id');
    }


    // علاقة مع جدول users
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // علاقة مع جدول general_journal
    public function dailyPage()
    {
        return $this->belongsTo(GeneralJournal::class, 'daily_page_id', 'page_id');
    }
    public function getTranslatedType()
    {
        return match ($this->daily_entries_type) {
            'سند صرف' => 'سند صرف',
            'سند قبض' => 'سند قبض',
            'رصيد افتتاحي' => 'رصيد افتتاحي',
            default => 'قيد يومي', // لا تعرض شيء في حالة "قيد يومي" أو أي شيء آخر
        };
    }
}
