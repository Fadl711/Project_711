<?php

namespace App\Http\Controllers\settingController\currenciesController;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencieController extends Controller
{
    //
    public function index(){
        $curr=Currency::all();
        return view('settings.currencies.index',compact('curr'));
    }
    public function create(){
        $curr=Currency::all();
        return view('settings.currencies.create',compact('curr'));
    }
    public function store(Request $request){
        Currency::create([

            'currency_name'=>$request->namecurr

        ]);
        return back();
    }
    public function edit($id){
        $curr=Currency::where('currency_id',$id)->first();
        return view('settings.currencies.edit',compact('curr'));
    }
    public function update(Request $request,$id){
            Currency::where('currency_id',$id)->update([
            'currency_name'=>$request->namecurr
        ]);

        return redirect()->route('settings.currencies.create');
    }
    public function destroy($id){
        Currency::where('currency_id',$id)->delete();
        return back();
    }
}
