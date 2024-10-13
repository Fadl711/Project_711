<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\PurchaseInvoice;
use Illuminate\Http\Request;

class InvoicePurchaseController extends Controller
{



public function index(){
    $Purchases=Purchase::all();
    return view('invoice_purchases.all_bills_purchase',compact('Purchases'));

}

public function bills_purchase_show($id){
    $Purchase=Purchase::where('purchase_id',$id)->first();


    return view('invoice_purchases.bills_purchase_show',compact('Purchase'));

}


    //
}
