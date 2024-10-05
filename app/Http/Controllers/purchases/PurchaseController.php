<?php

namespace App\Http\Controllers\purchases;

use App\Enum\AccountClass;
use App\Http\Controllers\Controller;
use App\Models\DefaultSupplier;
use App\Models\MainAccount;
use App\Models\SubAccount;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    //
    
    public function Purchase() {
    
                $defaultSupplier = DefaultSupplier::first();
                // dd($defaultSupplier);
    
                return view('Purchases.index', ['defaultSupplier'=>$defaultSupplier]);
          
    }}
