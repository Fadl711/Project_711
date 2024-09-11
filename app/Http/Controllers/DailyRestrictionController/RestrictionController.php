<?php

namespace App\Http\Controllers\DailyRestrictionController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RestrictionController extends Controller
{
    //
    public function create(){
        return view('daily_restrictions.create');
    }
    public function index(){
        return view('daily_restrictions.index');
    }
    
    
}
