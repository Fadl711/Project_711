<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerCoctroller extends Controller
{

    public function index(){

        return view('customers.index');
    }
    public function show(){

        return view('customers.show');
    }
    public function create(){

        return view('customers.create');
    }
}
