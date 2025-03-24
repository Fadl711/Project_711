<?php

namespace App\Http\Controllers;

use App\Enum\TransactionType;
use App\Models\AccountingPeriod;
use App\Models\DailyEntrie;
use App\Models\GeneralEntrie;
use Illuminate\Http\Request;

class generalEntrieController extends Controller
{
    //
    public function show()
    {
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
        $general_entries=[];
        
        $generalentries = GeneralEntrie::with(['subAccount','user'])
       -> where('accounting_period_id',$accountingPeriod->accounting_period_id)
        ->orderBy('id', 'asc')
        ->get();
        // dd($generalentries);
        
        $daily_entries_notFuond=[];
        foreach ($generalentries as $generalentry) {
    $daily_entries = DailyEntrie::where('entrie_id',$generalentry->daily_entry_id )->first();
    
    // if (!$daily_entries)
    //  {
    //     $daily_entries_notFuond[]=['id'=>$generalentry->id];

    //          } 

    if ($generalentry->type_account == '1') {
        $typeAccount = 'عميل';
    } elseif ($generalentry->type_account == '2') {
        $typeAccount = 'مورد';
    } elseif ($generalentry->type_account == '3') {
        $typeAccount = 'صندوق';
    } else {
        $typeAccount = ' ';
    }
    $payment_type = intval($generalentry->invoice_type);
    // تحديث أو إنشاء القيد
    // try {
        if($payment_type==1)
        {
           $paymenttype="نقدا";
        }
        if($payment_type==2)
        {
           $paymenttype="اجل";
        }
        if($payment_type==3)
        {
           $paymenttype="تحويل بنكي";
        }
        if($payment_type==4)
        {
           $paymenttype="شيك";
        }
    $general_entries[]=[
        'id'=>$generalentry->id,
        'subAccount'=>$generalentry->sub_account,
        'Main_id'=>$generalentry->main_id,
        'Daily_entry_id'=>$generalentry->daily_entry_id ??0,
        'Daily_Page_id'=>$generalentry->daily_Page_id ??0,
        'User_id'=>$generalentry->user,
        'General_ledger_page_number_id'=>$generalentry->general_ledger_page_number_id ??0,
        'accounting_period_id'=>$generalentry->accounting_period_id,
        // 'typeAccount'=>$typeAccount,
        'entry_type'=>($generalentry->entry_type=='debit'?'مدين':' دائن'),
        'amount'=>$generalentry->amount,
        'Currency_name'=>$generalentry->currency_name,
        'Invoice_type'=>  $generalentry->daily_entries_type." ". $paymenttype ,
        
        'Invoice_id'=>$generalentry->invoice_id,
        'description'=>$generalentry->description,
        'entry_date'=>$generalentry->entry_date,
        'entrie_id'=>$daily_entries->entrie_id ?? 'غير موجود',
    ];
}

// dd($general_entries);
return view('generalEntries.show',compact('general_entries'));


}
}
