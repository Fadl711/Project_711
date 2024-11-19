<?php

namespace App\Http\Controllers\Sale;

use App\Http\Controllers\Controller;
use App\Models\AccountingPeriod;
use App\Models\SaleInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InvoiceSaleController extends Controller
{
    //
    public function store(Request $request)
    {
      
    
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
        if (!$accountingPeriod) {
            return response()->json([
                'success' => false,
                'message' => 'لا توجد فترة محاسبية مفتوحة'
            ], 400);
        }
       // التحقق من صحة البيانات المدخلة
    $validatedData = $request->validate([
        'Customer_name_id' => 'nullable|exists:sub_accounts,sub_account_id',
        // 'payment_status' => 'required|in:paid,unpaid,partial',
        'total_price' => 'nullable|numeric|min:0',
        'total_price_sale' => 'nullable|numeric|min:0',
        'discount' => 'nullable|numeric|min:0',
        'User_id' => 'required|exists:users,id',
        'paid_amount' => 'nullable|numeric|min:0',
        'remaining_amount' => 'nullable|numeric|min:0',
        'payment_type' => 'required|in:cash,on_credit,transfer',
        'currency_id' => 'required|exists:currencies,currency_id', // assuming there's a currencies table
        'exchange_rate' => 'nullable|numeric|min:0',
        'shipping_bearer' => 'required|in:customer,merchant',
        'shipping_amount' => 'nullable|numeric|min:0',
    ]);

    // عملية الحفظ
    try {
        $salesInvoice = new SaleInvoice();
        $salesInvoice->Customer_id = $validatedData['Customer_name_id'];
        // $salesInvoice->payment_status = $validatedData['payment_status'];
        $salesInvoice->total_price = $validatedData['total_price']??0;
        $salesInvoice->total_price_sale = $validatedData['total_price_sale']??0;
        $salesInvoice->User_id = $validatedData['User_id'];
        $salesInvoice->paid_amount = $validatedData['paid_amount'] ?? 0;
        $salesInvoice->discount = $validatedData['discount'] ?? 0;
        $salesInvoice->shipping_amount = $validatedData['shipping_amount'] ?? 0;
        $salesInvoice->remaining_amount =0;
        $salesInvoice->payment_type = $validatedData['payment_type'];
        $salesInvoice->currency_id = $validatedData['currency_id'];
        $salesInvoice->exchange_rate = $validatedData['exchange_rate'] ?? 0;
        $salesInvoice->transaction_type ="مبيعات";
        $salesInvoice->shipping_bearer = $validatedData['shipping_bearer']??0;
        $salesInvoice->accounting_period_id = $accountingPeriod->accounting_period_id;
        $salesInvoice->save();

        return response()->json([
            'success' => true,
            'message' => 'تم الحفظ بنجاح',
            'invoice_number' => $salesInvoice->sales_invoice_id,
            'customer_number' => $salesInvoice->Customer_id,

        ], 201);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Failed to save the invoice.',
            'error' => $e->getMessage(),
        ], 500);
    }
}
        
    }
