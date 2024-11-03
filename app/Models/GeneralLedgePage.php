<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralLedgePage extends Model
{
    use HasFactory;
      // اسم الجدول المرتبط بالنموذج
      protected $table = 'general_ledge_page';

      // الأعمدة التي يمكن تعيينها بشكل جماعي
      protected $fillable = [
          'Amount_debtor',
          'Statement_debtor',
          'Daily_entry_debtor_id',
          'date_debtor',
          'Amount_creditor',
          'Statement_creditor',
          'Daily_entry_creditor_id',
          'date_creditor',
          'Daily_Page_id',
          'General_ledger_page_number_id',
          'accountingperiod_id',
      ];
  
      // العلاقات مع الجداول الأخرى
  
      // العلاقة مع جدول DailyEntries (المدين)
     
  
    //   public function generalJournalPage()
    //   {
    //       return $this->belongsTo(GeneralJournal::class, 'Daily_Page_id');
    //   }
  
    //   // العلاقة مع جدول GeneralLedge
    //   public function generalLedge()
    //   {
    //       return $this->belongsTo(GeneralLedge::class, 'General_ledger_page_number_id');
    //   }
  
    //   // العلاقة مع جدول AccountingPeriod
    //   public function accountingPeriod()
    //   {
    //       return $this->belongsTo(AccountingPeriod::class, 'accounting_period_id');
    //   }
}
