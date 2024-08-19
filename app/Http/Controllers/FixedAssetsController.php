<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FixedAssetsController extends Controller
{
    public function index(){
        return view('fixed_assets.index');
    }
}
