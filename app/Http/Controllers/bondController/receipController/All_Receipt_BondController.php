<?php

namespace App\Http\Controllers\bondController\receipController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class All_Receipt_BondController extends Controller
{
   
    public function show_all_receipt(){

        return view('bonds.receipt_bonds.all_receipt_bonds');
    }
}
