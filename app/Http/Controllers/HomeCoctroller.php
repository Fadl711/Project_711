<?php

namespace App\Http\Controllers;

use App\Enum\AccountClass;
use Illuminate\Http\Request;

class HomeCoctroller extends Controller
{

    
    public function indxe(){

        // تمرير القيم إلى الـ View
        return view('home.index');
    }
}
