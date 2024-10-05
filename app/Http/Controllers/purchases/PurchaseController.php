<?php

namespace App\Http\Controllers\purchases;

use App\Enum\AccountClass;
use App\Http\Controllers\Controller;
use App\Models\MainAccount;
use App\Models\SubAccount;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    //
    
    public function Purchase(){
    $supp=AccountClass::SUPPLIER->value;
    $mainAccount1=MainAccount::all();



    $supplirs1=SubAccount::where('Main_id', $mainAccount1->main_account_id)->get();

    $subAccount1=SubAccount::where('sub_name', $mainAccount1->account_name)->first();
       if(  $supplirs1!=null&&  $subAccount1!=null)
       {
        $mainAccount=MainAccount::where('AccountClass',$supp)->first();

        $supplirs=SubAccount::where('Main_id', $mainAccount->main_account_id)->get();

        $subAccount=SubAccount::where('sub_name', $mainAccount->account_name)->first();
        return view('Purchases.index',[$subAccount,$supplirs],compact('subAccount','supplirs'));

       } 
       else{
        return redirect()->route('home');
       }
    //    return view('Purchases.index',[$subAccount,$supplirs],compact('subAccount','supplirs'));

      

    


    }
}
