<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PurchaseCoctroller extends Controller
{
    //
    
    public function Purchase(){

        return view('Purchases.index');
    }
}
