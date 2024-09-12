<?php

namespace App\Http\Controllers\refundsController\purchasesController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Purchase_RefundController extends Controller
{
    //
    public function create(){
        return view('refunds.Purchases_refunds.create');
    }
}
