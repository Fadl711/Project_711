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
          ->whereIn('transaction_type', [TransactionType::PURCHASE->value, TransactionType::RETURN->value, TransactionType::INVENTORY_TRANSFER->value]) // هنا نستخدم القيم مباشرة من enum
          ->get();
        
        // استخدام map لتنسيق البيانات
        $purchaseInvoices = $purchaseInvoices->transform(function ($invoice) {
            return [
                'formatted_date' => $invoice->formatted_date ?? 'غير متاح',
                'supplier_name' => optional($invoice->supplier)->sub_name ?? 'غير معروف',
                'main_account_class' => optional($invoice->supplier?->mainAccount)->accountClassLabel() ?? 'غير معروف',
                'transaction_type' => TransactionType::tryFrom($invoice->transaction_type)?->label() ?? 'غير معروف',
                'invoice_number' => $invoice->purchase_invoice_id ?? 'غير متاح',
                'receipt_number' => $invoice->Receipt_number ?? 'غير متاح',
                'Invoice_type' => $invoice->Invoice_type ?? 'غير متاح',
                'total_invoice' => $invoice->Total_invoice ?? 0,
                'total_cost' => $invoice->Total_cost ?? 0,
                'paid' => $invoice->Paid ?? 0,
                'user_name' => $invoice->userName ?? 'غير معروف',
                'updated_at' => optional($invoice->updated_at)->format('Y-m-d') ?? 'غير متاح'
            ];
        });
        
        return view('invoice_purchases.all_bills_purchase', ['purchaseInvoices'=>$purchaseInvoices]);
    }
    


public function bills_purchase_show($id){
    $Purchase=Purchase::where('purchase_id',$id)->first();


    return view('invoice_purchases.bills_purchase_show',compact('Purchase'));

}



    //
}
