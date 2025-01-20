<?php

namespace App\Http\Controllers;

use App\Enum\AccountClass;
use App\Enum\PaymentType;
use App\Enum\TransactionType;
use App\Http\Controllers\Controller;
use App\Models\AccountingPeriod;
use App\Models\DailyEntrie;
use App\Models\MainAccount;
use App\Models\Purchase;
use App\Models\PurchaseInvoice;
use App\Models\SaleInvoice;
use App\Models\SubAccount;
use App\Models\User;
use Illuminate\Http\Request;

class InvoicePurchaseController extends Controller
{

    

    public function index()
    {
        // الحصول على آخر فترة محاسبية نشطة أو الافتراضية
        // $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
    
        // if (!$accountingPeriod) {
        //     return redirect()->back()->with('error', 'لم يتم العثور على فترة محاسبية حالية.');
        // }
    
        // $purchaseInvoices = PurchaseInvoice::with([
        //     'supplier.mainAccount',
        //     'user'
        // ])->where('accounting_period_id', $accountingPeriod->accounting_period_id)
        //   ->whereIn('transaction_type', [TransactionType::PURCHASE->value, TransactionType::RETURN->value, TransactionType::INVENTORY_TRANSFER->value ,TransactionType::RETURN_SALE->value]) // هنا نستخدم القيم مباشرة من enum
        //   ->get();
        // // استخدام map لتنسيق البيانات
        // $purchaseInvoices = $purchaseInvoices->transform(function ($invoice) {
        //     return [
        //         'formatted_date' => $invoice->formatted_date ?? 'غير متاح',
        //         'supplier_name' => optional($invoice->supplier)->sub_name ?? 'غير معروف',
        //         'main_account_class' => optional($invoice->supplier?->mainAccount)->accountClassLabel() ?? 'غير معروف',
        //         'transaction_type' => TransactionType::tryFrom($invoice->transaction_type)?->label() ?? 'غير معروف',
        //         'invoice_number' => $invoice->purchase_invoice_id ?? 'غير متاح',
        //         'receipt_number' => $invoice->Receipt_number ?? 'غير متاح',
        //         'Invoice_type' => $invoice->Invoice_type ?? 'غير متاح',
        //         'total_invoice' => $invoice->Total_invoice ?? 0,
        //         'total_cost' => $invoice->Total_cost ?? 0,
        //         'paid' => $invoice->Paid ?? 0,
        //         'user_name' => $invoice->userName ?? 'غير معروف',
        //         'updated_at' => optional($invoice->updated_at)->format('Y-m-d') ?? 'غير متاح'
        //     ];
        // });
        
        return view('invoice_purchases.all_bills_purchase');
    }
    public function getPurchaseInvoices(Request $request, $filterType)
    {
        $validated = $request->validate([
      
            'fromDate' => 'nullable',
            'toDate' => 'nullable',
        ]);
        // الحصول على آخر فترة محاسبية نشطة
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
    
        if (!$accountingPeriod) {
            return response()->json(['message' => 'لم يتم العثور على فترة محاسبية حالية.'], 404);
        }
    
        // إنشاء استعلام الفواتير
        $query = PurchaseInvoice::with(['supplier.mainAccount', 'user'])
            ->whereIn('transaction_type', [
                TransactionType::PURCHASE->value,
                TransactionType::RETURN->value,
                TransactionType::INVENTORY_TRANSFER->value,
            ]);
    
        // تطبيق الفلترة بناءً على نوع الفلترة
        switch ($filterType) {
            case '1': // تلقائي (الفترة المحاسبية الحالية)
                $query->where('accounting_period_id', $accountingPeriod->accounting_period_id);
                break;
            case '2': // اليوم
                $query->where('accounting_period_id', $accountingPeriod->accounting_period_id);

                $query->whereDate('created_at', now()->toDateString());
                break;
            case '3': // هذا الأسبوع
                $query->where('accounting_period_id', $accountingPeriod->accounting_period_id);

                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case '4':// هذا الشهر

                $query->where('accounting_period_id', $accountingPeriod->accounting_period_id);
                $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                break;
            case '5':// ه
                if ($request->filled(['fromDate', 'toDate'])) 
                {
                    $query->whereBetween('created_at', [$validated['fromDate'], $validated['toDate']]);
                    break;
                }
         
        }
        $purchaseInvoices = $query->get()->transform(function ($invoice) {
            return [
                'formatted_date' => $invoice->formatted_date ?? 'غير متاح',
                'supplier_name' => optional($invoice->supplier)->sub_name ?? 'غير معروف',
                'main_account_class' => optional($invoice->supplier?->mainAccount)->accountClassLabel() ?? 'غير معروف',
         'transaction_type' => TransactionType::tryFrom($invoice->transaction_type)?->label() ?? 'غير معروف',
        'invoice_number' => $invoice->purchase_invoice_id ?? 'غير متاح',
                'receipt_number' => $invoice->Receipt_number ?? 'غير متاح',
                'Invoice_type' => PaymentType::tryFrom($invoice->Invoice_type)?->label() ?? 'غير معروف',
                'total_invoice' => $invoice->Total_invoice ?? 0,
                'total_cost' => $invoice->Total_cost ?? 0,
                'paid' => $invoice->Paid ?? 0,
                'user_name' => $invoice->userName ?? 'غير معروف',
                'updated_at' => optional($invoice->updated_at)->format('Y-m-d') ?? 'غير متاح',
                'view_url' => route('invoicePurchases.print', $invoice->purchase_invoice_id),
            // 'edit_url' => route('receip.edit', $invoice->sales_invoice_id),
            'destroy_url' => route('purchase-invoice.delete', $invoice->purchase_invoice_id),
      
            ];
        });
        return response()->json(['purchaseInvoices' => $purchaseInvoices], 200);
    }
    public function deleteInvoice($id)
{
    try {
        $invoice = PurchaseInvoice::where('purchase_invoice_id', $id)->first();
        if (!$invoice) {
            return response()->json([
                'success' => false,
                'message' => 'لم يتم العثور على معرف الفاتورة.'
            ]);      
          }
          $transaction_type=  TransactionType::fromValue($invoice->transaction_type)?->label();

        // حذف جميع المشتريات المرتبطة إن وجدت
        if ($invoice->purchases()->exists()) {
            $invoice->purchases()->delete();
        }
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
        if (!$accountingPeriod) {
            return response()->json(['success' => false, 'message' => 'لا توجد فترة محاسبية مفتوحة.']);
        }
        // حذف الفاتورة نفسها
        $invoice->delete();
        $Getentrie_id = DailyEntrie::where('Invoice_id',$id)
            ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
            ->where('daily_entries_type',$transaction_type)
            ->first();
            if($Getentrie_id )
            {
                $Getentrie_id->delete();
            }

        // DB::commit();
        return response()->json([
            'success' => true,
            'message' => 'تم حذف الفاتورة وجميع المشتريات المرتبطة بها بنجاح'
        ]);

    } catch (\Exception $e) {
        // DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => 'حدث خطأ أثناء الحذف: ' . $e->getMessage()
        ]);
    }
}
    public function searchInvoices(Request $request)
    {
       
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
    
        if (!$accountingPeriod) {
            return response()->json(['message' => 'لم يتم العثور على فترة محاسبية حالية.'], 404);
        }
        // التحقق من المدخلات
        $validated = $request->validate([
            'searchType' => 'nullable|string|in:كل الفواتير,أول فاتورة,آخر فاتورة',
            'searchQuery' => 'nullable|string|max:255',
            'fromDate' => 'nullable',
            'toDate' => 'nullable',
        ]);
        // بناء الاستعلام الأساسي
        $query = PurchaseInvoice::with(['supplier', 'user'])
            ->whereIn('transaction_type', [
                TransactionType::PURCHASE->value,
                TransactionType::RETURN->value,
                TransactionType::INVENTORY_TRANSFER->value,
            ]);    
            if ($validated['searchQuery'] ?? false) {
                $searchQuery = $validated['searchQuery'];
            
                $query->where(function ($query) use ($searchQuery) {
                    // البحث باستخدام رقم الفاتورة
                    $query->where('purchase_invoice_id','like', $searchQuery . '%')
                    // البحث باستخدام اسم المورد
                    ->orWhereHas('supplier', function ($query) use ($searchQuery) {
                        $query->where('sub_name', 'like', $searchQuery . '%'); // البحث عن الأسماء التي تبدأ بالقيمة المدخلة
                    });
                });
            }
    
        // ترتيب الفواتير حسب نوع البحث
        if ($request->filled(['fromDate', 'toDate'])) {
            // dd($validated['fromDate'], $validated['toDate']);

            $query->whereBetween('created_at', [$validated['fromDate'], $validated['toDate']]);
        }
        else
        {
            $query->where('accounting_period_id', $accountingPeriod->accounting_period_id);
            if ($validated['searchType'] && $validated['searchType'] !== 'كل الفواتير') {
                $orderDirection = ($validated['searchType'] === 'أول فاتورة') ? 'asc' : 'desc';
                $query->orderBy('created_at', $orderDirection);
            }

        }
        // الحصول على النتائج
        $purchaseInvoices  = $query->get()->transform(function ($invoice) {
          
            return [
                'formatted_date' => $invoice->formatted_date ?? 'غير متاح',
                'supplier_name' => optional($invoice->supplier)->sub_name ?? 'غير معروف', // عرض اسم المورد
                'main_account_class' => optional($invoice->supplier?->mainAccount)->accountClassLabel() ?? 'غير معروف',
                'transaction_type' => TransactionType::fromValue($invoice->transaction_type)?->label() ?? 'غير معروف',
                'invoice_number' => $invoice->purchase_invoice_id ?? 'غير متاح',
                'receipt_number' => $invoice->Receipt_number ?? 'غير متاح',
                'Invoice_type' => PaymentType::tryFrom($invoice->Invoice_type)?->label() ?? 'غير معروف',
                'total_invoice' => $invoice->Total_invoice ?? 0,
                'total_cost' => $invoice->Total_cost ?? 0,
                'paid' => $invoice->Paid ?? 0,
                'user_name' => $invoice->userName ?? 'غير معروف',
                'updated_at' => optional($invoice->updated_at)->format('Y-m-d') ?? 'غير متاح',
                'view_url' => route('invoicePurchases.print', $invoice->purchase_invoice_id),
                // 'edit_url' => route('receip.edit', $invoice->sales_invoice_id),
                'destroy_url' => route('purchase-invoice.delete', $invoice->purchase_invoice_id),
          
            ];
        });
    
        // إرجاع النتائج
        return response()->json([
            'purchaseInvoices' => $purchaseInvoices
        ]);
    }
    
public function bills_purchase_show($id){
    $Purchase=Purchase::where('purchase_id',$id)->first();
    return view('invoice_purchases.bills_purchase_show',compact('Purchase'));

}

    public function GetInvoiceNumber($id)
{        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();

    if (in_array($id, [4, 5])) {
        // استرجاع الفواتير من جدول SaleInvoice
        $invoices = SaleInvoice::where('transaction_type', $id)
        ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
        ->get();
    } elseif (in_array($id, [1, 2, 3])) {
        // استرجاع الفواتير من جدول PurchaseInvoice
        $invoices = PurchaseInvoice::where('transaction_type', $id)
        ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
        ->get();
    } else {
        // إذا لم يكن $id يطابق أي من القيم
        return response()->json(['message' => 'Invalid transaction type'], 400);
    }

    return response()->json($invoices);
}


public function getSalesByInvoiceArrowLeft(Request $request)
{
    $invoiceId = $request->input('purchase_invoice_id');
    $user_id = auth()->id();

    // جلب أول فاتورة أكبر من الفاتورة الحالية
    $SaleInvoice = PurchaseInvoice::where('purchase_invoice_id', '>', $invoiceId)
        ->orderBy('purchase_invoice_id', 'asc') // ترتيب تصاعدي
        ->first();

    if (!$SaleInvoice) {
        return response()->json(['message' => 'لا توجد فاتورة لاحقة.'], 404);
    }

    if (!$SaleInvoice) {
        return response()->json(['message' => 'لا توجد فاتورة لاحقة.'], 404);
    }

    // جلب المبيعات المرتبطة بالفاتورة المحددة
    $sales = Purchase::where('Purchase_invoice_id', $SaleInvoice->Purchase_invoice_id)
    ->get();

    if ($sales->isEmpty()) {
        return response()->json(['message' => 'لا توجد مبيعات مرتبطة بهذه الفاتورة.'], 404);
    }
    return response()->json([
        'sales' => $sales,
        'last_invoice_id' => $SaleInvoice->purchase_invoice_id,
        'SaleInvoice' => $SaleInvoice,
    ]);
}


public function getSalesByInvoiceArrowRight(Request $request)
{
    $invoiceId = $request->input('purchase_invoice_id');
    dd( $invoiceId );
    $user_id = auth()->id();
      
    // جلب أول فاتورة أكبر من الفاتورة الحالية
    $SaleInvoice = PurchaseInvoice::where('purchase_invoice_id', '>', $invoiceId)
        ->orderBy('purchase_invoice_id', 'asc') // ترتيب تصاعدي
        ->first();
    if (!$SaleInvoice) {
        return response()->json(['message' => 'لا توجد فاتورة لاحقة.'], 404);
    }
    // جلب المبيعات المرتبطة بالفاتورة المحددة
    $sales = Purchase::where('Purchase_invoice_id', $SaleInvoice->purchase_invoice_id)->get();
    if ($sales->isEmpty())
     {
        return response()->json(['message' => 'لا توجد مبيعات مرتبطة بهذه الفاتورة.']);
     }
    return response()->json([
        'sales' => $sales,
        'last_invoice_id' => $SaleInvoice->purchase_invoice_id,
    ]);
}




    //
}
