<?php

namespace App\Http\Controllers\SaleCoctroller;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Default_customer;
use App\Models\SubAccount;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    //
    public function create(){
        $customers=SubAccount::where('AccountClass',1)->get();
        $DefaultCustomer  = Default_customer::where('id',1)->pluck('subaccount_id')->first();
        $Currency_name=Currency::all();


        return view('sales.create',['customers'=>$customers,
        'DefaultCustomer'=>$DefaultCustomer
        ,'Currency_name'=>$Currency_name
    ]);
    }
}
