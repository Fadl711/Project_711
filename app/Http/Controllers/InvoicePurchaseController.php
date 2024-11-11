<?php

namespace App\Http\Controllers;

use App\Enum\AccountClass;
use App\Enum\TransactionType;
use App\Http\Controllers\Controller;
use App\Models\AccountingPeriod;
use App\Models\MainAccount;
use App\Models\Purchase;
use App\Models\PurchaseInvoice;
use App\Models\SubAccount;
use App\Models\User;
use Illuminate\Http\Request;

class InvoicePurchaseController extends Controller
{


    public function index()
    {
        // الحصول على آخر فترة محاسبية نشطة أو الافتراضية
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
    
        if (!$accountingPeriod) {
            return redirect()->back()->with('error', 'لم يتم العثور على فترة محاسبية حالية.');
        }
    
        $purchaseInvoices = PurchaseInvoice::with([
            'supplier.mainAccount',
            'user'
        ])->where('accounting_period_id', $accountingPeriod->accounting_period_id)
          ->whereIn('transaction_type', [TransactionType::PURCHASE->value, TransactionType::RETURN->value]) // هنا نستخدم القيم مباشرة من enum
          ->get();
        
        // استخدام map لتنسيق البيانات
        $purchaseInvoices = $purchaseInvoices->map(function ($invoice) {
            return [
                'formatted_date' => $invoice->formatted_date,
                'supplier_name' => $invoice->supplier->sub_name ?? 'غير معروف',
                'main_account_class' => $invoice->supplier->mainAccount->accountClassLabel() ?? 'غير معروف',  // استخدام دالة label هنا
                'transaction_type' => TransactionType::from($invoice->transaction_type)->label(), // استخدام enum للحصول على التسمية
                'invoice_number' => $invoice->purchase_invoice_id,
                'receipt_number' => $invoice->Receipt_number,
                'Invoice_type' => $invoice->Invoice_type,

                'total_invoice' => $invoice->Total_invoice,
                'total_cost' => $invoice->Total_cost,
                'paid' => $invoice->Paid,
                'user_name' => $invoice->userName,
                'updated_at' => $invoice->updated_at->format('Y-m-d')
            ];
        });
    
        return view('invoice_purchases.all_bills_purchase', compact('purchaseInvoices'));
    }
    

public function bills_purchase_show($id){
    $Purchase=Purchase::where('purchase_id',$id)->first();


    return view('invoice_purchases.bills_purchase_show',compact('Purchase'));

}


    //
}
