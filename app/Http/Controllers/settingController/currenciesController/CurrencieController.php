<?php

namespace App\Http\Controllers\settingController\currenciesController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CurrencieController extends Controller
{
    //
    public function index(){
        return view('settings.currencies.index');
    }
}
