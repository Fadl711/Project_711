<?php

namespace App\Http\Controllers\refundsController\purchasesController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Purchase_RefundController extends Controller
{
    //
    public function show_purchase_refund(){
        return view('refunds.Purchases_refunds.show_purchases_refund');
    }
    public function show(){
        return view('refunds.Purchases_refunds.show');
    }
    public function create(){
        return view('refunds.Purchases_refunds.create');
    }
}
