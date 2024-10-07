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
            // الحصول على SubAccount بناءً على main_account_id
            $supplirx = SubAccount::where('Main_id', $customers->main_account_id)->get();
        return view('settings.default_customers.index', compact('defaultSuppliers','SubAccounts','supplirx'));
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
        Default_customer::where('id',$id)->delete();
        return redirect()->route('default_customers.index')->with('success', 'customers deleted successfully.');
    }}


