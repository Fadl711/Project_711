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
    public function   all_restrictions_show(){
        return view('daily_restrictions.all_restrictions_show');
    }

    public function   edit(){
        return view('daily_restrictions.edit');
    }
    public function   show(){
        return view('daily_restrictions.show');
    }
    
}
