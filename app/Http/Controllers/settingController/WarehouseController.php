<?php

namespace App\Http\Controllers\settingController;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index(){
       $Warehouses=Warehouse::all();
        return view('settings.warehouse.index',compact('Warehouses'));
    }
    public function store(Request $request){
        Warehouse::create([

            'Store_name'=>$request->Store_name,
            'Store_location'=>$request->Store_location,
            'Stock_level'=>$request->Stock_level,
            'user_id'=>$request->user_id,

        ]);
        return redirect()->back()->withInput();
    }
    public function edit($id){
        $Warehouse=Warehouse::where('warehouse_id',$id)->first();
        return view('settings.warehouse.edit',compact('Warehouse'));
    }
    public function update(Request $request,$id){
        Warehouse::where('warehouse_id',$id)->update([
            'Store_name'=>$request->Store_name,
            'Store_location'=>$request->Store_location,
            'Stock_level'=>$request->Stock_level,
            'user_id'=>$request->user_id,
        ]);

        return redirect()->route('warehouse.index');
    }
    public function destroy($id){
        Warehouse::where('warehouse_id',$id)->delete();
        return redirect()->back();
    }

}
