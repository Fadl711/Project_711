<?php

namespace App\Http\Controllers\settingController;

use App\Enum\AccountClass;
use App\Http\Controllers\Controller;
use App\Models\DefaultSupplier;
use App\Models\MainAccount;
use App\Models\SubAccount;
use Illuminate\Http\Request;

class default_supplierController extends Controller
{
    public function index()
    {

        $defaultSuppliers = DefaultSupplier::all();
        if($defaultSuppliers===null)
        {
            return view('settings.default_suppliers.index');

        }
        else
{  
          return view('settings.default_suppliers.index', compact('defaultSuppliers'));
} 
   }
    public function create()
    {
        $suppliers = MainAccount::where('AccountClass', AccountClass::SUPPLIER->value)->first();
if ($suppliers) {
    // الحصول على SubAccount بناءً على main_account_id
    $supplirx = SubAccount::where('main_id', $suppliers->main_account_id)->get();
    
    // dd($supplirx);
    return view('settings.default_suppliers.create',[ 'supplirx'=>$supplirx]);
}
        else {
    return view('settings.default_suppliers.create');
}

    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subaccount_id' => 'required|integer',
            'debtor_amount' => 'nullable|numeric',
            'creditor_amount' => 'nullable|numeric',
            'name_The_known' => 'nullable|string|max:255',
            'Known_phone' => 'nullable|string|max:255',
            'User_id' => 'required|integer',
            'Phone' => 'nullable|string|max:15',
        ]);

        DefaultSupplier::create($request->all());

        return redirect()->route('default_suppliers.index')->with('success', 'Supplier created successfully.');
    }

    public function edit(DefaultSupplier $supplier)
    {
        return view('default_suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, DefaultSupplier $supplier)
    {
        $request->validate([
            'name' => 'required',
            'Phone' => 'required',
            'subaccount_id' => 'required|integer',
        ]);

        $supplier->update($request->all());
        return redirect()->route('default_suppliers.index')->with('success', 'Supplier updated successfully.');
    }

    public function destroy(DefaultSupplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('default_suppliers.index')->with('success', 'Supplier deleted successfully.');
    }}
