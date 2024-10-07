<?php

namespace App\Http\Controllers\SaleCoctroller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    //
    public function index(){

        return view('sales.index');
    }
}
