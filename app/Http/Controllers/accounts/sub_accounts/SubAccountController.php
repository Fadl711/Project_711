<?php

namespace App\Http\Controllers\Accounts\sub_accounts;

use App\Http\Controllers\Controller;
use App\Models\SubAccount;
use Illuminate\Http\Request;

class SubAccountController extends Controller
{
    //
    
    public function storeItem(Request $request)
    {
        // قم بالتحقق من صحة البيانات'invoice_id' => 'required|exists:invoices,id', 
        // $request->validate([
            
        //     'sub_name' => 'required|string|max:255',
        //     'debtor' => 'required|integer|min:1',
        //     'creditor' => 'required|numeric|min:0',
        // ]);

        // إنشاء الصنف وإضافته إلى الفاتورة  'invoice_id' => $request->invoice_id,
        $item = SubAccount::create([
           
            'sub_name' => $request->item_name,
            'debtor' => $request->quantity,
            'creditor' => $request->price,
        ]);

        // إرجاع البيانات على شكل JSON لاستخدامها في الواجهة الأمامية
        return response()->json($item);
    }
    public function getItems($account_id)
    {
        // $invoiceId
        $items = SubAccount::all()->where($account_id);

        return response()->json($items);
        // return ['items' => $item];//+

    }

}
