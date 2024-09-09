<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class reportsConreoller extends Controller
{
    public function index(){
        return view('report.index');
    }
}
