<?php

namespace App\Http\Controllers\bondController\exchangeController;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\ExchangeBond;
use App\Models\GeneralJournal;
use App\Models\MainAccount;
use App\Models\PaymentBond;
use App\Models\SubAccount;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExchangeController extends Controller
{
    public function create(){
        return view('bonds.receipt_bonds.index');
    }
    public function index(){
        $mainAccount=MainAccount::all();
        $curr=Currency::all();
        // الحصول على تاريخ اليوم
        $today = Carbon::now()->toDateString(); // الحصول على تاريخ اليوم بصيغة YYYY-MM-DD
        $dailyPage = GeneralJournal::whereDate('created_at', $today)->first(); // البحث عن الصفحة
        return view('bonds.exchange_bonds.index',compact('curr','dailyPage'),['mainAccounts'=> $mainAccount,$dailyPage]);
     }
     public function store(Request $request){
        if($request->DepositAccount==$request->CreditAmount){
        return response()->json(['error' => ' لايمكن اختيار نفس الحساب']);

        }else{
            ExchangeBond::create([
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


    }
     public function all_exchange_bonds(){
        $PaymentBonds=ExchangeBond::all();
        $SubAccounts=SubAccount::all();
       $MainAccounts= MainAccount::all();
       $Currencies=Currency::all();
        return view('bonds.exchange_bonds.all_exchange_bonds',compact('PaymentBonds','SubAccounts','MainAccounts','Currencies'));
     }
     public function show($id){

        $PaymentBond=ExchangeBond::where('payment_bond_id',$id)->first();
        return view('bonds.exchange_bonds.show',compact('PaymentBond'));
    }
    public function edit($id){
        $ExchangeBond=ExchangeBond::where('payment_bond_id',$id)->first();
        $mainAccount=MainAccount::all();
        $SubAccounts=SubAccount::all();
        $curr=Currency::all();
        // الحصول على تاريخ اليوم
        $today = Carbon::now()->toDateString(); // الحصول على تاريخ اليوم بصيغة YYYY-MM-DD
        $dailyPage = GeneralJournal::whereDate('created_at', $today)->first(); // البحث عن الصفحة
        $ExchangeBond=ExchangeBond::where('payment_bond_id',$id)->first();
        return view('bonds.exchange_bonds.edit',compact('curr','dailyPage','ExchangeBond','SubAccounts'),['mainAccounts'=> $mainAccount,$dailyPage]);
    }
    public function update(Request $request){
        ExchangeBond::where('payment_bond_id',$request->id)->update([
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

        return redirect()->route('all_exchange_bonds');
    }
    public function destroy($id){
        ExchangeBond::where('payment_bond_id',$id)->delete();

        return redirect()->route('all_exchange_bonds');
}
public function print($id){
    $PaymentBond=ExchangeBond::where('payment_bond_id',$id)->first();
    return view('bonds.exchange_bonds.print',compact('PaymentBond'));
}



}
