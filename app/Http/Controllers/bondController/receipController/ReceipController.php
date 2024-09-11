<?php

namespace App\Http\Controllers\bondController\receipController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReceipController extends Controller
{
    public function create(){

        return view('bonds.receipt_bonds.index');
    }
    public function show(){

        return view('bonds.receipt_bonds.show');    }
        public function edit(){

            return view('bonds.receipt_bonds.edit');
        }
}
