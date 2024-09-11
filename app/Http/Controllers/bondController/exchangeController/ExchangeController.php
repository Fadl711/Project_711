<?php

namespace App\Http\Controllers\bondController\exchangeController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExchangeController extends Controller
{
    public function create(){

        return view('bonds.receipt_bonds.index');
    }
    public function index(){

        return view('bonds.exchange_bonds.index');   
     }
     public function all_exchange_bonds(){

        return view('bonds.exchange_bonds.all_exchange_bonds');   
     }
     public function show(){

        return view('bonds.exchange_bonds.show');
    }
    public function edit(){

        return view('bonds.exchange_bonds.edit');
    }
}
