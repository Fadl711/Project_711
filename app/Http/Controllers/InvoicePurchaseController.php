<?php

namespace App\Http\Controllers\invoicepurchasessController;

use App\Http\Controllers\Controller;
use App\Models\PurchaseInvoice;
use Illuminate\Http\Request;

class InvoicePurchaseController extends Controller
{
    public function all_bills_purchase(){
        
        return view('invoice_purchases.all_bills_purchase');
    }

  
public function index(){
    $ff="ff";  
    dd($ff);
        
    return view('invoice_purchases.index');

}

public function bills_purchase_show(){
    
        
    return view('invoice_purchases.bills_purchase_show');

}
    //
}
