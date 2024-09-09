<?php

namespace App\Http\Controllers\bondController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BondController extends Controller
{
    public function bonds(){

        return view('bonds.index');
    }
}
