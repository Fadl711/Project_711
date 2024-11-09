<?php

namespace App\Http\Controllers\bondController\receipController;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\GeneralJournal;
use App\Models\MainAccount;
use App\Models\PaymentBond;
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
        return response()->json(['success' => 'تم بنجاح']);

    }
    public function show($id){

        $PaymentBond=PaymentBond::where('payment_bond_id',$id)->first();
        return view('bonds.receipt_bonds.show',compact('PaymentBond'));
    }
        public function edit(){

            return view('bonds.receipt_bonds.edit');
        }
        public function destroy($id){
            PaymentBond::where('payment_bond_id',$id)->delete();

            return redirect()->route('show_all_receipt');
}
public function print($id){
    $PaymentBond=PaymentBond::where('payment_bond_id',$id)->first();
    return view('bonds.receipt_bonds.print',compact('PaymentBond'));
}
}