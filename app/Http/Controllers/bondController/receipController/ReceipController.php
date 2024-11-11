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
        PaymentBond::create([
            'Main_debit_account_id'=>$request->AccountReceivable,
            'Debit_sub_account_id'=>$request->DepositAccount,
            'Amount_debit'=>$request->Amount_debit,
            'Main_Credit_account_id'=>$request->PaymentParty,
            'Credit_sub_account_id'=>$request->CreditAmount,
            'Statement'=>$request->Statement,
            'Currency_id'=>$request->Currency,
            'User_id'=>$request->User_id,
            'created_at'=>$request->date,
        ]);



        // الحصول على تاريخ اليوم بصيغة YYYY-MM-DD
        $today = Carbon::now()->toDateString();
        $dailyPage = GeneralJournal::whereDate('created_at', $today)->first();

        // إذا لم توجد صفحة، قم بإنشائها
        if (!$dailyPage) {
            $dailyPage = GeneralJournal::create([]);
        }
        // حفظ القيد اليومي
        $dailyEntrie = new DailyEntrie();


    $dailyEntrie->account_debit_id = $request->DepositAccount;
    $dailyEntrie->Amount_debit = $request->Amount_debit;
    $dailyEntrie->account_Credit_id = $request->CreditAmount;
    $dailyEntrie->Amount_Credit = 0;
    $dailyEntrie->Statement =
    $dailyEntrie->Currency_name = $request->Statement; // استخدم الاسم الصحيح هنا
    $dailyEntrie->accounting_period_id = $accountingPeriod->accounting_period_id;
    $dailyEntrie->Daily_page_id = $dailyPage->page_id; // حفظ معرف الصفحة اليومية
    $dailyEntrie->User_id = $request->User_id;
    $dailyEntrie->save();


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
            PaymentBond::where('payment_bond_id',$request->id)->update([
                'Main_debit_account_id'=>$request->AccountReceivable,
                'Debit_sub_account_id'=>$request->DepositAccount,
                'Amount_debit'=>$request->Amount_debit,
                'Main_Credit_account_id'=>$request->PaymentParty,
                'Credit_sub_account_id'=>$request->CreditAmount,
                'Statement'=>$request->Statement,
                'Currency_id'=>$request->Currency,
                'User_id'=>$request->User_id,
                'created_at'=>$request->date,
            ]);

            return redirect()->route('show_all_receipt');
        }
        public function destroy($id){
            PaymentBond::where('payment_bond_id',$id)->delete();

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

