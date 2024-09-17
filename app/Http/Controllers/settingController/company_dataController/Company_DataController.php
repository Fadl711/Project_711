<?php

namespace App\Http\Controllers\settingController\company_dataController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Company_DataController extends Controller
{
    //
    public function create(){
        return view('settings.company_data.create');
    }
}
