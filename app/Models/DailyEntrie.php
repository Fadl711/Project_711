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
    'Amount_debit',
    'account_debit_id',
    'Amount_Credit',
    'account_Credit_id',
    'Statement',
    'Daily_page_id',
    'Currency_name',
    'User_id',
    'Invoice_type',
    'Invoice_id',
    'accounting_period_id',
    'status_debit',
    'status',
    'daily_entries_type',
    'exchange_rate',
];
       // تحديد أنواع الحقول
       protected $casts = [
        'Amount_debit' => 'decimal:2',
        'Amount_Credit' => 'decimal:2',
        'Daily_page_id' => 'integer',
        'User_id' => 'integer',
        'Invoice_id' => 'integer',
        'accounting_period_id' => 'integer',
        'account_debit_id' => 'integer',
        'account_Credit_id' => 'integer',
        'status_debit' => 'string',
        'status' => 'string',
    ];
    public function subAccounts() {
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
      return $this->belongsTo(SubAccount::class, 'account_Credit_id', 'sub_account_id');
  }


  // علاقة مع جدول users
  public function user()
  {
      return $this->belongsTo(User::class, 'User_id', 'id');
  }

  // علاقة مع جدول general_journal
  public function dailyPage()
  {
      return $this->belongsTo(GeneralJournal::class, 'Daily_page_id', 'page_id');
  }

}
