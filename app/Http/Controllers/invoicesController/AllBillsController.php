<?php

namespace App\Http\Controllers\invoicesController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AllBillsController extends Controller
{
    //
    public function all_bills(){
        
        return view('invoice.all_bills');
    }
}
