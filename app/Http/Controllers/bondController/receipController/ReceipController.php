<?php

namespace App\Http\Controllers\bondController\receipController;

use App\Http\Controllers\Controller;
use App\Models\AccountingPeriod;
use App\Models\Currency;
use App\Models\DailyEntrie;
use App\Models\GeneralJournal;
use App\Models\MainAccount;
use App\Models\PaymentBond;
use App\Models\SubAccount;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReceipController extends Controller
{
    public function create(){
        $mainAccount=MainAccount::all();
        $curr=Currency::all();
        // الحصول على تاريخ اليوم
        $today = Carbon::now()->toDateString(); // الحصول على تاريخ اليوم بصيغة YYYY-MM-DD
        $dailyPage = GeneralJournal::whereDate('created_at', $today)->first(); // البحث عن الصفحة
        return view('bonds.receipt_bonds.index',compact('curr','dailyPage'),['mainAccounts'=> $mainAccount,$dailyPage]);
    }
    public function store(Request $request){
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
        if($request->DepositAccount==$request->CreditAmount){
            return response()->json(['error' => ' لايمكن اختيار نفس الحساب']);
        }
       $paymentBond= PaymentBond::create([
            'Main_debit_account_id'=>$request->AccountReceivable,
            'Debit_sub_account_id'=>$request->DepositAccount,
            'Amount_debit'=>$request->Amount_debit,
            'Main_Credit_account_id'=>$request->PaymentParty,
            'Credit_sub_account_id'=>$request->CreditAmount,
            'Statement'=>$request->Statement ?? "سند قبض",
            'Currency_id'=>$request->Currency,
            'User_id'=>$request->User_id,
            'created_at'=>$request->date,
        ]);

        // if($paymentBond->payment_bond_id){
        //     return response()->json(['success' => '  اختيار نفس الحساب'.$paymentBond->payment_bond_id]);
        // }
        $paymentBond1= PaymentBond::where('payment_bond_id',$paymentBond->payment_bond_id)->first();

        // الحصول على تاريخ اليوم بصيغة YYYY-MM-DD
        $today = Carbon::now()->toDateString();
        $dailyPage = GeneralJournal::whereDate('created_at', $today)->first();

        // إذا لم توجد صفحة، قم بإنشائها
        if (!$dailyPage) {
            $dailyPage = GeneralJournal::create([]);
        }

        $transaction_type="سند قبض";

        $Getentrie_id = DailyEntrie::where('Invoice_id', $paymentBond1->payment_bond_id)
            ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
            ->where('daily_entries_type',$transaction_type)
            ->value('entrie_id');
    
        // Create or update the daily entry
        DailyEntrie::updateOrCreate(
            [
                'entrie_id' => $Getentrie_id,
                'accounting_period_id' => $accountingPeriod->accounting_period_id,
                'Invoice_id' => $paymentBond1->payment_bond_id,
                'daily_entries_type' => $transaction_type,
            ],
            [
                'account_debit_id' => $paymentBond1->Debit_sub_account_id,
                'Amount_Credit' => $paymentBond1->Amount_debit ?: 0,
                'Amount_debit' => $paymentBond1->Amount_debit ?: 0,
                'account_Credit_id' => $paymentBond1->Credit_sub_account_id,
                'Statement' =>  $paymentBond1->Statement,
                'Daily_page_id' => $dailyPage->page_id,
                'Invoice_type' => "نقداً",
                'Currency_name' => 'ر',
                'User_id' => auth()->user()->id,
                'status_debit' => 'غير مرحل',
                'status' => 'غير مرحل',
            ]
        );
       


        return response()->json(['success' => 'تم بنجاح']);

    }


    public function show($id){

        $PaymentBond=PaymentBond::where('payment_bond_id',$id)->first();
        return view('bonds.receipt_bonds.show',compact('PaymentBond'));
    }
        public function edit($id){
            $ExchangeBond=PaymentBond::where('payment_bond_id',$id)->first();
            $mainAccount=MainAccount::all();
            $SubAccounts=SubAccount::all();
            $curr=Currency::all();
            // الحصول على تاريخ اليوم
            $today = Carbon::now()->toDateString(); // الحصول على تاريخ اليوم بصيغة YYYY-MM-DD
            $dailyPage = GeneralJournal::whereDate('created_at', $today)->first(); // البحث عن الصفحة

            return view('bonds.receipt_bonds.edit',compact('curr','dailyPage','ExchangeBond','SubAccounts'),['mainAccounts'=> $mainAccount,$dailyPage]);
        }
        public function update(Request $request){
            $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
            if($request->DepositAccount==$request->CreditAmount){
                return response()->json(['error' => ' لايمكن اختيار نفس الحساب']);
            }
            $today = Carbon::now()->toDateString();
            $dailyPage = GeneralJournal::whereDate('created_at', $today)->first();
            $PaymentBond=PaymentBond::where('payment_bond_id',$request->id)->first();
            $Currency=Currency::where('currency_id',$request->Currency)->value('currency_name');
            PaymentBond::where('payment_bond_id',$request->id)->update([
                'Main_debit_account_id'=>$request->AccountReceivable,
                'Debit_sub_account_id'=>$request->DepositAccount,
                'Amount_debit'=>$request->Amount_debit,
                'Main_Credit_account_id'=>$request->PaymentParty,
                'Credit_sub_account_id'=>$request->CreditAmount,
                'Statement'=>$request->Statement ?? "سند قبض",
                'Currency_id'=>$request->Currency,
                'User_id'=>$request->User_id,
                'created_at'=>$request->date,
            ]);
            $transaction_type="سند قبض";
            $Getentrie_id = DailyEntrie::where('Invoice_id', $PaymentBond->payment_bond_id)
                ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
                ->where('daily_entries_type',$transaction_type)
                ->value('entrie_id');
        
            // Create or update the daily entry
            DailyEntrie::updateOrCreate(
                [
                    'entrie_id' => $Getentrie_id,
                    'accounting_period_id' => $accountingPeriod->accounting_period_id,
                    'Invoice_id' => $PaymentBond->payment_bond_id,
                    'daily_entries_type' => $transaction_type,
                ],
                [
                    'account_debit_id' => $PaymentBond->Debit_sub_account_id,
                    'Amount_Credit' => $PaymentBond->Amount_debit ?: 0,
                    'Amount_debit' => $PaymentBond->Amount_debit ?: 0,
                    'account_Credit_id' => $PaymentBond->Credit_sub_account_id,
                    'Statement' =>  $transaction_type . "نقداً",
                    'Daily_page_id' => $dailyPage->page_id,
                    'Invoice_type' => "نقداً",
                    'Currency_name' => 'ر',
                    'User_id' => auth()->user()->id,
                    'status_debit' => 'غير مرحل',
                    'status' => 'غير مرحل',
                ]
            );
           
    


            return redirect()->route('show_all_receipt');
        }
        public function destroy($id){
            $transaction_type="سند قبض";
            $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();

            $PaymentBond=PaymentBond::where('payment_bond_id',$id)->first();
           
            PaymentBond::where('payment_bond_id',$id)->delete();
            $PaymentBond1=PaymentBond::where('payment_bond_id',$id)->first();

            if(!$PaymentBond1)
            {
                $Getentrie_id = DailyEntrie::where('Invoice_id', $PaymentBond->payment_bond_id)
                ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
                ->where('daily_entries_type',$transaction_type)
                ->delete('entrie_id');
                if($Getentrie_id)
                {
                    DailyEntrie::where('entrie_id',$Getentrie_id)->delete();
                }

            }
            return redirect()->route('show_all_receipt');



            
}
public function print($id){
    $PaymentBond=PaymentBond::where('payment_bond_id',$id)->first();
    return view('bonds.receipt_bonds.print',compact('PaymentBond'));
}
public function stor(Request $request){
    $today = Carbon::now()->toDateString(); // الحصول على تاريخ اليوم بصيغة YYYY-MM-DD
    $dailyPage = GeneralJournal::whereDate('created_at', $today)->first(); // البحث عن الصفحة
    if ($dailyPage) {
            $generalJournal1=GeneralJournal::all();
            $mainAccount=MainAccount::all();
        $curr=Currency::all();
        return view('bonds.receipt_bonds.index',compact('curr','dailyPage'),['mainAccounts'=> $mainAccount]);
    } else {
        $Statement=$request->Statement;
             GeneralJournal::create([
            ]);
            // الحصول على تاريخ اليوم بصيغة YYYY-MM-DD
            $today = Carbon::now()->toDateString();
            $dailyPage = GeneralJournal::whereDate('created_at', $today)->first(); // البحث عن الصفحة
            if ($dailyPage) {
                // إذا تم العثور على الصفحة، عرض رقم الصفحة
                $generalJournal1=GeneralJournal::all();
                $mainAccount=MainAccount::all();
            // dd($generalJournal1);
            $curr=Currency::all();
            return view('bonds.receipt_bonds.index',compact('curr','dailyPage'),['mainAccounts'=> $mainAccount]);
            }
    }

}

}

