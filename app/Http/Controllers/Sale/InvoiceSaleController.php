<?php

namespace App\Http\Controllers\Sale;

use App\Enum\PaymentType;
use App\Enum\TransactionType;
use App\Http\Controllers\Controller;
use App\Models\AccountingPeriod;
use App\Models\Category;
use App\Models\Currency;
use App\Models\DailyEntrie;
use App\Models\Sale;
use App\Models\SaleInvoice;
use App\Models\SubAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use NumberToWords\NumberToWords;

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
        'payment_type' => 'required|numeric',
        'financial_account_id' => 'required|numeric',
        'currency_id' => 'required|exists:currencies,currency_id', // assuming there's a currencies table
        'exchange_rate' => 'nullable|numeric|min:0',
        'shipping_bearer' => 'required|in:customer,merchant',
        'transaction_type' => 'required|numeric',
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
        $salesInvoice->account_id = $validatedData['financial_account_id'];
        $salesInvoice->currency_id = $validatedData['currency_id'];
        $salesInvoice->exchange_rate = $validatedData['exchange_rate'] ?? 0;
        $salesInvoice->transaction_type =$validatedData['transaction_type'];
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


public function getSaleInvoice(Request $request, $filterType)
{
    $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();

    if (!$accountingPeriod) {
        return response()->json(['message' => 'لم يتم العثور على فترة محاسبية حالية.'], 404);
    }

    $query = SaleInvoice::with(['customer.mainAccount', 'user'])
        ->where('accounting_period_id', $accountingPeriod->accounting_period_id);

    switch ($filterType) {
        case '2':
            $query->whereDate('created_at', now()->toDateString());
            break;
        case '3':
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            break;
        case '4':
            $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
            break;
        default:
            $fromDate = $request->input('fromDate');
            $toDate = $request->input('toDate');
            if ($fromDate && $toDate) {
                try {
                    $fromDate = Carbon::parse($fromDate);
                    $toDate = Carbon::parse($toDate);
                } catch (\Exception $e) {
                    return response()->json(['message' => 'تنسيق التواريخ غير صحيح.'], 400);
                }
                $query->whereBetween('created_at', [$fromDate, $toDate]);
            }
            break;
    }

    $SaleInvoice = $query->get()->transform(function ($invoice) {
        return [
            'formatted_date' => $invoice->formatted_date ?? 'غير متاح',
            'Customer_name' => optional($invoice->customer)->sub_name ?? 'غير معروف',
            'main_account_class' => optional($invoice->customer?->mainAccount)->accountClassLabel() ?? 'غير معروف',
            'transaction_type' => TransactionType::fromValue($invoice->transaction_type)?->label() ?? 'غير معروف',
            'invoice_number' => $invoice->sales_invoice_id ?? 'غير متاح',
            'discount' => $invoice->discount ?? 'غير متاح',
            'payment_type' => PaymentType::tryFrom($invoice->payment_type)?->label() ?? 'غير معروف',
            'shipping_bearer' => $invoice->shipping_bearer ?? 'غير متاح',
            'shipping_amount' => $invoice->shipping_amount ?? 0,
            'total_price_sale' => $invoice->total_price_sale ?? 0,
            'net_total_after_discount' => $invoice->net_total_after_discount ?? 0,
            'paid_amount' => $invoice->paid_amount ?? 0,
            'remaining_amount' => $invoice->remaining_amount ?? 0,
            'user_name' => $invoice->userName ?? 'غير معروف',
            'updated_at' => optional($invoice->updated_at)->format('Y-m-d') ?? 'غير متاح',
            'view_url' => route('searchInvoices', $invoice->sales_invoice_id),
            'edit_url' => route('receip.edit', $invoice->sales_invoice_id),
            'destroy_url' => route('sales-invoice.delete', $invoice->sales_invoice_id),
        ];
    });

    return response()->json(['saleInvoice' => $SaleInvoice]);
}


public function print($id)
{
    $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();

    $DataPurchaseInvoice = SaleInvoice::where('sales_invoice_id', $id)->first();
    $SubAccount = SubAccount::where('sub_account_id', $DataPurchaseInvoice->Customer_id)->first();
    $UserName = User::where('id', $DataPurchaseInvoice->User_id)->pluck('name')->first();


    if (!$UserName) {
        $UserName = 'اسم غير موجود';
    }
        $SubName = SubAccount::all();
    if($SubAccount->AccountClass===1)
    {
        $AccountClassName="العميل";
    }

    if($SubAccount->AccountClass===2)
    {
        $AccountClassName="المورد";
    }
    if($SubAccount->AccountClass===3)
    {
        $AccountClassName="المخزن";
    }
    if($SubAccount->AccountClass===4)
    {
        $AccountClassName="الحساب";
    }

    if($DataPurchaseInvoice->payment_type===1)
    {
        $paymentype="نقداً";
    }
    $DataSale = Sale::where('Invoice_id', $id)->get();
    $Categorys = Category::all();
   $curre=Currency::where('currency_id', $DataPurchaseInvoice->currency_id)->first();
    // حساب مجموع السعر والتكلفة
    $Sale_priceSum = Sale::where('Invoice_id', $id)->sum('total_price');
    $Sale_CostSum = Sale::where('Invoice_id', $id)->sum('total_amount');
    $SumDebtor_amount=DailyEntrie::where('account_debit_id',$SubAccount->sub_account_id)->sum('Amount_debit');
    $SumCredit_amount=DailyEntrie::where('account_Credit_id',$SubAccount->sub_account_id)->sum('Amount_Credit');
    $query = DailyEntrie::with(['debitAccount', 'debitAccount.mainAccount', 'creditAccount', 'creditAccount.mainAccount'])
    ->selectRaw(
        '
         SUM(CASE WHEN daily_entries.account_debit_id = sub_accounts.sub_account_id THEN daily_entries.Amount_debit ELSE 0 END) as total_debit,
         SUM(CASE WHEN daily_entries.account_Credit_id = sub_accounts.sub_account_id THEN daily_entries.Amount_Credit ELSE 0 END) as total_credit'
    )
    ->join('sub_accounts', function ($join) {
        $join->on('daily_entries.account_debit_id', '=', 'sub_accounts.sub_account_id')
             ->orOn('daily_entries.account_Credit_id', '=', 'sub_accounts.sub_account_id');
    })
    ->where('sub_accounts.sub_account_id', $SubAccount->sub_account_id); 
    // إضافة الشرط للحساب الفرعي
    $query->where('daily_entries.accounting_period_id',$accountingPeriod->accounting_period_id);
    $entriesTotally = $query->get();
    $SumDebtor_amount = $entriesTotally->sum('total_debit');
    $SumCredit_amount = $entriesTotally->sum('total_credit');  

    $Sum_amount=$SumDebtor_amount-$SumCredit_amount;
    // تحويل القيمة إلى نص مكتوب
    $numberToWords = new NumberToWords();
    $numberTransformer = $numberToWords->getNumberTransformer('ar'); // اللغة العربية
    
    return view('invoice_sales.bills_sale_show', [
        'DataPurchaseInvoice' => $DataPurchaseInvoice,
        'DataSale' => $DataSale,
        'SubAccounts' => $SubAccount,
        'Sale_priceSum' => $Sale_priceSum,
        'Sale_CostSum' => $Sale_CostSum,
        'priceInWords' => is_numeric($Sale_priceSum) 
        ? $numberTransformer->toWords($Sale_priceSum) . ' ' . $curre->currency_name
        : 'القيمة غير صالحة', // القيمة النصية
        'Categorys' => $Categorys,
        'currency' => $curre->currency_name,
        'payment_type' => PaymentType::tryFrom($DataPurchaseInvoice->payment_type)?->label() ?? 'غير معروف',
        'transaction_type' => TransactionType::fromValue($DataPurchaseInvoice->transaction_type)?->label() ?? 'غير معروف',
        'warehouses' => $SubName,
        'UserName' => $UserName,
        'accountCla' => $AccountClassName,
        'Sum_amount' => $Sum_amount,
    ]);

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
    ]);

    // بناء الاستعلام الأساسي
    $query = SaleInvoice::with(['customer', 'user'])
        ->where('accounting_period_id', $accountingPeriod->accounting_period_id);
        if ($validated['searchQuery'] ?? false) {
            $searchQuery = $validated['searchQuery'];
        
            $query->where(function ($query) use ($searchQuery) {
                // البحث باستخدام رقم الفاتورة
                $query->where('sales_invoice_id','like', $searchQuery . '%')
                
                // البحث باستخدام اسم المورد
                ->orWhereHas('customer', function ($query) use ($searchQuery) {
                    $query->where('sub_name', 'like', $searchQuery . '%'); // البحث عن الأسماء التي تبدأ بالقيمة المدخلة
                });
            });
        }

    // ترتيب الفواتير حسب نوع البحث
    if ($validated['searchType'] && $validated['searchType'] !== 'كل الفواتير') {
        $orderDirection = ($validated['searchType'] === 'أول فاتورة') ? 'asc' : 'desc';
        $query->orderBy('created_at', $orderDirection);
    }

    // الحصول على النتائج
    $SaleInvoice = $query->get()->transform(function ($invoice) {
        return [
            'formatted_date' => $invoice->formatted_date ?? 'غير متاح',
            'Customer_name' => optional($invoice->customer)->sub_name ?? 'غير معروف',
            'main_account_class' => optional($invoice->customer?->mainAccount)->accountClassLabel() ?? 'غير معروف',
            'transaction_type' => $invoice->transaction_type ?? 'غير معروف',
            'invoice_number' => $invoice->sales_invoice_id ?? 'غير متاح',
            'discount' => $invoice->discount ?? 'غير متاح',
            'payment_type' => PaymentType::tryFrom($invoice->payment_type)?->label() ?? 'غير معروف',
            'transaction_type' => TransactionType::fromValue($invoice->transaction_type)?->label() ?? 'غير معروف',
            'shipping_bearer' => $invoice->shipping_bearer ?? 'غير متاح',
            'shipping_amount' => $invoice->shipping_amount ?? 0,
            'total_price_sale' => $invoice->total_price_sale ?? 0,
            'net_total_after_discount' => $invoice->net_total_after_discount ?? 0,
            'paid_amount' => $invoice->paid_amount ?? 0,
            'remaining_amount' => $invoice->remaining_amount ?? 0,
            'user_name' => $invoice->userName ?? 'غير معروف',
            'updated_at' => optional($invoice->updated_at)->format('Y-m-d') ?? 'غير متاح',
        ];
    });

    return response()->json(['saleInvoice' => $SaleInvoice]);
}




        
    }
