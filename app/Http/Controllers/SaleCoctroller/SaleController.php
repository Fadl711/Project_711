<?php

namespace App\Http\Controllers\SaleCoctroller;

use App\Http\Controllers\Controller;
use App\Models\Default_customer;
use App\Models\SubAccount;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    //
    public function index(){
        $customers=SubAccount::where('AccountClass',1)->get();
        $DefaultCustomer  = Default_customer::where('id',1)->pluck('subaccount_id')->first();

        return view('sales.index',['customers'=>$customers,'DefaultCustomer'=>$DefaultCustomer]);
    }
}
