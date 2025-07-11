<?php

namespace App\Http\Controllers;

use App\Enum\AccountClass;
use App\Enum\PaymentType;
use App\Enum\TransactionType;
use App\Http\Controllers\Controller;
use App\Models\AccountingPeriod;
use App\Models\DailyEntrie;
use App\Models\GeneralEntrie;
use App\Models\MainAccount;
use App\Models\Purchase;
use App\Models\PurchaseInvoice;
use App\Models\SaleInvoice;
use App\Models\SubAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

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
    // Controller
    public function getPurchaseInvoices(Request $request, $filterType)
    {
        // تحقق من الفترة المحاسبية مع التخزين المؤقت
        $accountingPeriod = Cache::remember('active_accounting_period', 3600, function () {
            return AccountingPeriod::where('is_closed', false)->first();
        });

        if (!$accountingPeriod) {
            return response()->json(['message' => 'لم يتم العثور على فترة محاسبية حالية.'], 404);
        }

        // بناء الاستعلام مع تحديد العلاقات المطلوبة
        $query = PurchaseInvoice::with([
            'supplier:sub_account_id,sub_name,main_id',
            'supplier.mainAccount:main_account_id,AccountClass',
            'user:id,name'
        ])->whereIn('transaction_type', [
            TransactionType::PURCHASE->value,
            TransactionType::RETURN->value,
            TransactionType::INVENTORY_TRANSFER->value,
        ]);

        // تطبيق الفلاتر
        switch ($filterType) {
            case '1':
                $query->where('accounting_period_id', $accountingPeriod->accounting_period_id);
                break;
            case '2':
                $query->where('accounting_period_id', $accountingPeriod->accounting_period_id)
                    ->whereDate('created_at', now()->toDateString());
                break;
            case '3':
                $query->where('accounting_period_id', $accountingPeriod->accounting_period_id)
                    ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case '4':
                $query->where('accounting_period_id', $accountingPeriod->accounting_period_id)
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
                break;
            case '5':
                if ($request->filled(['fromDate', 'toDate'])) {
                    $query->whereBetween('created_at', [
                        $request->input('fromDate') . ' 00:00:00',
                        $request->input('toDate') . ' 23:59:59'
                    ]);
                }
                break;
        }

        // استخدام الترحيم
        $purchaseInvoices = $query->paginate(50);

        // تحويل البيانات
        $transformedInvoices = $purchaseInvoices->getCollection()->map(function ($invoice) {
            return [
                'formatted_date' => $invoice->created_at->format('d/m/Y'),
                'supplier_name' => $invoice->supplier->sub_name ?? 'غير معروف',
                'main_account_class' => $invoice->supplier->mainAccount->AccountClass ?? 'غير معروف',
                'transaction_type' => TransactionType::tryFrom($invoice->transaction_type)?->label() ?? 'غير معروف',
                'invoice_number' => $invoice->purchase_invoice_id,
                'receipt_number' => $invoice->Receipt_number ?? 'غير متاح',
                'Invoice_type' => PaymentType::tryFrom($invoice->Invoice_type)?->label() ?? 'غير معروف',
                'total_invoice' => $invoice->Total_invoice ?? 0,
                'total_cost' => $invoice->Total_cost ?? 0,
                'paid' => $invoice->Paid ?? 0,
                'user_name' => $invoice->user->name ?? 'غير معروف',
                'updated_at' => $invoice->updated_at->format('Y-m-d'),
                'view_url' => route('invoicePurchases.print', $invoice->purchase_invoice_id),
                'destroy_url' => route('purchase-invoice.delete', $invoice->purchase_invoice_id),
            ];
        });

        return response()->json([
            'purchaseInvoices' => $transformedInvoices,
            'pagination' => [
                'current_page' => $purchaseInvoices->currentPage(),
                'last_page' => $purchaseInvoices->lastPage(),
                'per_page' => $purchaseInvoices->perPage(),
                'total' => $purchaseInvoices->total(),
            ]
        ]);
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
            $transaction_type =  TransactionType::fromValue($invoice->transaction_type)?->label();
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
            $Getentrie_id = DailyEntrie::where('Invoice_id', $id)
                ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
                ->where('daily_entries_type', $transaction_type)
                ->first();
            if ($Getentrie_id->entrie_id) {
                $generalEntrieaccount_debit_id = GeneralEntrie::where([
                    'daily_entry_id' => $Getentrie_id->entrie_id,
                    'accounting_period_id' => $Getentrie_id->accounting_period_id,
                    'sub_id' => $Getentrie_id->account_debit_id,
                ])->delete();
                $generalEntrieaccount_debit_id = GeneralEntrie::where([
                    'daily_entry_id' => $Getentrie_id->entrie_id,
                    'accounting_period_id' => $Getentrie_id->accounting_period_id,
                    'sub_id' => $Getentrie_id->account_credit_id,
                ])->delete();
            }
            if ($Getentrie_id->entrie_id) {
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
                $query->where('purchase_invoice_id', 'like', $searchQuery . '%')
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
        } else {
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

    public function bills_purchase_show($id)
    {
        $Purchase = Purchase::where('purchase_id', $id)->first();
        return view('invoice_purchases.bills_purchase_show', compact('Purchase'));
    }

    public function GetInvoiceNumber($id)
    {
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();

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


    public function getpurchasesByInvoiceArrowLeft(Request $request)
    {
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();

        $invoiceId = $request->input('purchase_invoice_id');
        // dd($invoiceId);
        $user_id = auth()->id();

        // جلب أول فاتورة أكبر من الفاتورة الحالية
        $PurchaseInvoice = PurchaseInvoice::where('purchase_invoice_id', '>', $invoiceId)
            ->where('accounting_period_id', $accountingPeriod->accounting_period_id)

            ->orderBy('purchase_invoice_id', 'asc') // ترتيب تصاعدي
            ->first();



        if (!$PurchaseInvoice) {
            return response()->json(['message' => 'لا توجد فاتورة لاحقة.']);
        }

        // جلب المبيعات المرتبطة بالفاتورة المحددة
        $sales = Purchase::where('Purchase_invoice_id', $PurchaseInvoice->purchase_invoice_id)
            ->get();
        $SubAccount = SubAccount::where('sub_account_id', $PurchaseInvoice->Supplier_id)->first();
        $Suppliers = SubAccount::where('account_class', 2)->where('sub_account_id', '!=', $SubAccount->sub_account_id)
            ->get();
        $Supplier_name = $SubAccount->sub_name;
        $Supplier_id = $SubAccount->sub_account_id;
        $TransactionTypes = [];
        $TransactionTyS = TransactionType::cases();

        $label = TransactionType::fromValue($PurchaseInvoice->transaction_type)?->label() ?? 'غير معروف';
        $valueType = TransactionType::fromValue($PurchaseInvoice->transaction_type)?->value;
        foreach ($TransactionTyS as $TransactionType) {
            if (in_array($TransactionType->value, [1, 2, 3]) && $TransactionType->value != $valueType) {
                // التحقق من أن الكائن ليس null
                if ($TransactionType->label() && $TransactionType->value) {
                    $TransactionTypes[] = [
                        'value' => $TransactionType->value,
                        'label' => $TransactionType->label(),
                    ];
                }
            }
        }


        if ($sales->isEmpty()) {
            return response()->json(['message' => 'لا توجد مبيعات مرتبطة بهذه الفاتورة.']);
        }
        // dd($PurchaseInvoice->Supplier_id);
        return response()->json([
            'sales' => $sales,
            'suppliers' => $Suppliers,

            'Supplier_name' => $SubAccount->sub_name,
            'SupplierId' => $SubAccount->sub_account_id,
            'TransactionTypes' => $TransactionTypes,
            'transaction_typelabel' => $label,
            'transaction_valueType' => $valueType,
            'last_invoice_id' => $PurchaseInvoice->purchase_invoice_id,
            'SaleInvoice' => $PurchaseInvoice,
        ]);
    }


    public function getpurchasesByInvoiceArrowRight(Request $request)
    {
        $invoiceId = $request->input('purchase_invoice_id');
        // dd( $invoiceId );
        $user_id = auth()->id();

        // جلب أول فاتورة أكبر من الفاتورة الحالية
        $PurchaseInvoice = PurchaseInvoice::where('purchase_invoice_id', '<', $invoiceId)
            ->orderBy('purchase_invoice_id', 'desc') // ترتيب تصاعدي
            ->first();
        if (!$PurchaseInvoice) {
            return response()->json(['message' => 'لا توجد فاتورة سابقة.'], 404);
        }
        // جلب المبيعات المرتبطة بالفاتورة المحددة
        $sales = Purchase::where('Purchase_invoice_id', $PurchaseInvoice->purchase_invoice_id)->get();
        if ($sales->isEmpty()) {
            return response()->json(['message' => 'لا توجد مبيعات مرتبطة بهذه الفاتورة.']);
        }
        $SubAccount = SubAccount::where('sub_account_id', $PurchaseInvoice->Supplier_id)->first();
        $Suppliers = SubAccount::where('account_class', 2)->where('sub_account_id', '!=', $SubAccount->sub_account_id)
            ->get();
        $Supplier_name = $SubAccount->sub_name;
        $Supplier_id = $SubAccount->sub_account_id;
        $TransactionTypes = [];
        $TransactionTyS = TransactionType::cases();

        $label = TransactionType::fromValue($PurchaseInvoice->transaction_type)?->label() ?? 'غير معروف';
        $valueType = TransactionType::fromValue($PurchaseInvoice->transaction_type)?->value;
        foreach ($TransactionTyS as $TransactionType) {
            if (in_array($TransactionType->value, [1, 2, 3]) && $TransactionType->value != $valueType) {
                // التحقق من أن الكائن ليس null
                if ($TransactionType->label() && $TransactionType->value) {
                    $TransactionTypes[] = [
                        'value' => $TransactionType->value,
                        'label' => $TransactionType->label(),
                    ];
                }
            }
        }


        if ($sales->isEmpty()) {
            return response()->json(['message' => 'لا توجد مبيعات مرتبطة بهذه الفاتورة.']);
        }
        // dd($PurchaseInvoice->Supplier_id);
        return response()->json([
            'sales' => $sales,
            'suppliers' => $Suppliers,

            'Supplier_name' => $SubAccount->sub_name,
            'SupplierId' => $SubAccount->sub_account_id,
            'TransactionTypes' => $TransactionTypes,
            'transaction_typelabel' => $label,
            'transaction_valueType' => $valueType,
            'last_invoice_id' => $PurchaseInvoice->purchase_invoice_id,
            'SaleInvoice' => $PurchaseInvoice,
        ]);
    }




    //
}
