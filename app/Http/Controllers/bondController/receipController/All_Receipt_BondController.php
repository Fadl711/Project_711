<?php

namespace App\Http\Controllers\bondController\receipController;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\MainAccount;
use App\Models\PaymentBond;
use App\Models\SubAccount;
use App\Models\User;
use Illuminate\Http\Request;

class All_Receipt_BondController extends Controller
{

    public function show_all_receipt(){
        $PaymentBonds=PaymentBond::all();
        $SubAccounts=SubAccount::all();
       $MainAccounts= MainAccount::all();
       $Currencies=Currency::all();
        return view('bonds.receipt_bonds.all_receipt_bonds',compact('PaymentBonds','SubAccounts','MainAccounts','Currencies'));
    }
}
