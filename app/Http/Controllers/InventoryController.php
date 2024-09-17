<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(){

        return view('inventory.index');
    }
    public function create(){

        return view('inventory.create');
    }
    public function  show_inventory(){

        return view('inventory.show_inventory');
    }
    public function  show(){

        return view('inventory.show');
    }

}
