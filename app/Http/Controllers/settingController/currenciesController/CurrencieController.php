<?php

namespace App\Http\Controllers\settingController\currenciesController;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\CurrencySetting;
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

            'currency_name'=>$request->namecurr,
            'currency_symbol'=>$request->symbol

        ]);
        return back();
    }
    public function edit($id){
        $curr=Currency::where('currency_id',$id)->first();
        return view('settings.currencies.edit',compact('curr'));
    }
    public function update(Request $request,$id){
            Currency::where('currency_id',$id)->update([
            'currency_name'=>$request->namecurr,
            'currency_symbol'=>$request->symbol
        ]);

        return redirect()->route('settings.currencies.create');
    }
    public function destroy($id){
        Currency::where('currency_id',$id)->delete();
        return back();
    }

    public function setDefaultCurrency(Request $request){
        $currencyId = $request->input('currency_id');
        // Update the default currency in the database
        // For example, using Eloquent :
        CurrencySetting::updateOrCreate(
            ['currency_settings_id' => 1], // assuming the ID is 1, adjust accordingly
            ['Currency_id' => $currencyId]
        );

        return response()->json(['success' => true]);
    }
}
