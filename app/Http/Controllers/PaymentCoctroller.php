<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PurchaseInvoice;
use Illuminate\Http\Request;

class PaymentCoctroller extends Controller
{
    public function index(){

        return view('payments.index');
    }

}
