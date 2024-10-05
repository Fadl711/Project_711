<?php

namespace App\Http\Controllers;

use App\Enum\AccountClass;
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

          return view('settings.default_customers.index', compact('defaultSuppliers','SubAccounts'));

   }
    public function create()
    {

        $customers =MainAccount::where('AccountClass', AccountClass::CUSTOMER->value)->first();
if ($customers) {
    // الحصول على SubAccount بناءً على main_account_id
    $supplirx = SubAccount::where('Main_id', $customers->main_account_id)->get();

    // dd($supplirx);
    return view('settings.default_customers.create',[ 'supplirx'=>$supplirx]);
}
        else {
    return view('settings.default_customers.create');
}

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


        return redirect()->route('default_customers.index')->with('success', 'customers created successfully.');
    }

    public function edit( $supplier)
    {
        return view('default_suppliers.edit', compact('supplier'));
    }

    public function update(Request $request,  $supplier)
    {
        $request->validate([
            'name' => 'required',
            'Phone' => 'required',
            'subaccount_id' => 'required|integer',
        ]);

        $supplier->update($request->all());
        return redirect()->route('default_suppliers.index')->with('success', 'Supplier updated successfully.');
    }

    public function destroy( $supplier)
    {
        $supplier->delete();
        return redirect()->route('default_suppliers.index')->with('success', 'Supplier deleted successfully.');
    }}


