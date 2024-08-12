<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeCoctroller extends Controller
{
    public function indxe(){
        return view('home.indxe');
    }
}
