<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DoubleEntryController extends Controller
{
    public function create(){
        return view("daily_restrictions.double_entries.create");
    }
    public function storeOrUpdate(){

    }
    public function delete(){

    }
}
