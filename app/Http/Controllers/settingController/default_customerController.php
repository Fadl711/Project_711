<?php

namespace App\Http\Controllers\settingController;

use App\Enum\AccountClass;
use App\Http\Controllers\Controller;
use App\Models\Default_customer;
use App\Models\MainAccount;
use App\Models\SubAccount;
use Illuminate\Http\Request;

class default_customerController extends Controller
{
    public function index()
    {
        $defaultSuppliers = Default_customer::first();
        $SubAccounts=SubAccount::all();
        $customers =MainAccount::where('AccountClass', AccountClass::CUSTOMER->value)->first();
        if($customers){

            $supplirx = SubAccount::where('Main_id', $customers->main_account_id)->get();
        }else{
            $supplirx=null;
        }

        $warehouse =MainAccount::where('AccountClass', AccountClass::STORE->value)->first();
        if($warehouse){
            $warehouse1 = SubAccount::where('Main_id', $warehouse->main_account_id)->get();
        }else{
            $warehouse1=null;
        }

        $box =MainAccount::where('AccountClass', AccountClass::BOX->value)->first();
        if($box){
            $box1 = SubAccount::where('Main_id', $box->main_account_id)->get();
        }else{
            $box1=null;
        }

        return view('settings.default_customers.index', compact('defaultSuppliers','SubAccounts','supplirx','warehouse1','box1'));
   }
    public function store(Request $request)
    {
            $Default_customer = $request->input('subaccount_id');
            // Update the default currency in the database
            // For example, using Eloquent :
            Default_customer::updateOrCreate(
                ['id' => 1], // assuming the ID is 1, adjust accordingly
                ['subaccount_id' => $Default_customer]
            );
            return response()->json(['success' => true]);
    }
    public function destroy($id)
    {
        Default_customer::where('subaccount_id',$id)->update([
            'subaccount_id'=>null,
        ]);
        return redirect()->route('default_customers.index')->with('success', 'customers deleted successfully.');
    }
    public function storeWarehouse(Request $request)
    {

        $Default_customer = $request->input('warehouse_id');
        // Update the default currency in the database
        // For example, using Eloquent :
        Default_customer::updateOrCreate(
            ['id' => 1], // assuming the ID is 1, adjust accordingly
            ['warehouse_id' => $Default_customer]
        );
        return response()->json(['success' => true]);
    }
    public function destroyWarehouse($id)
    {
        Default_customer::where('warehouse_id',$id)->update([
            'warehouse_id'=>null,
        ]);
        return redirect()->route('default_customers.index')->with('success', 'customers deleted successfully.');
    }
    public function storeFinancial(Request $request)
    {
            $Default_customer = $request->input('financial_account_id');
            // Update the default currency in the database
            // For example, using Eloquent :
            Default_customer::updateOrCreate(
                ['id' => 1], // assuming the ID is 1, adjust accordingly
                ['financial_account_id' => $Default_customer]
            );
            return response()->json(['success' => true]);
    }
    public function destroyFinancial($id)
    {
        Default_customer::where('financial_account_id',$id)->update([
            'financial_account_id'=>null,
        ]);
        return redirect()->route('default_customers.index')->with('success', 'customers deleted successfully.');
    }

}


