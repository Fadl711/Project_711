<?php

namespace App\Http\Controllers\Sale;

use App\Enum\PaymentType;
use App\Enum\TransactionType;
use App\Http\Controllers\Controller;
use App\Models\AccountingPeriod;
use App\Models\Category;
use App\Models\Currency;
use App\Models\DailyEntrie;
use App\Models\Default_customer;
use App\Models\GeneralJournal;
use App\Models\Sale;
use App\Models\SaleInvoice;
use App\Models\SubAccount;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use NumberToWords\NumberToWords;

class InvoiceSaleController extends Controller
{
    //
    public function store(Request $request)
    {
      
 $user=auth()->id();
 $AuthorityName="الفواتير المبيعات";
 $us=UserPermission::where('User_id', $user)
 ->where('Authority_Name',$AuthorityName)
 ->first();
 if (optional($us)->Writing_ability == 1) {
    
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
        if (!$accountingPeriod) {
            return response()->json([
                'success' => false,
                'message' => 'لا توجد فترة محاسبية مفتوحة'
            ], 400);
        }
       // التحقق من صحة البيانات المدخلة
    $validatedData = $request->validate([
        'date' => 'nullable|date', // تأكد من إضافة هذا الحقل
        'listRadio' => 'required|in:1,2', // تحديث القيم هنا
        'Customer_name_id' => 'nullable|exists:sub_accounts,sub_account_id',
        'total_price' => 'nullable|numeric|min:0',
        'total_price_sale' => 'nullable|numeric|min:0',
        'discount' => 'nullable|numeric|min:0',
        'User_id' => 'required|exists:users,id',
        'paid_amount' => 'nullable|numeric|min:0',
        'remaining_amount' => 'nullable|numeric|min:0',
        'payment_type' => 'required|numeric',
        'note' => 'nullable',
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
       
        $invoice_id=SaleInvoice::where('sales_invoice_id',$request->invoice_id)->first();

    if($invoice_id)
    {
        return response()->json([
            'success' => false,
            'message' => 'الفاتورة موجودة من قبل',

        ], 201);
    }
    else
    {
        if($request->invoice_id)
        {
            $salesInvoice->sales_invoice_id =$request->invoice_id;

        }
       
    }
    if( $validatedData['listRadio']==2)
    {
        $salesInvoice->created_at =  $validatedData['date'];
    }
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
        $salesInvoice->note = $validatedData['note'];
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
     
 } else {
    return response()->json([
        'success' => false,
        'message' => 'لا يوجد لديك صلاحية لإضافة فاتورة',
    ], 500);
    return view('auth.login');
}
}
public function update(Request $request)
{
    // dd( $request->sales_invoice_id);
    $user = auth()->id();
    $AuthorityName = "الفواتير المبيعات";
    $transactionType = intval($request->transaction_type); // أو (int)$request->transaction_type

    // التحقق من وجود الفترة المحاسبية
    $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
    if (!$accountingPeriod) {
        return response()->json(['success' => false, 'message' => 'لا توجد فترة محاسبية مفتوحة.']);
    }

    // التحقق من صحة البيانات المدخلة
    $validatedData = $request->validate([
        'Customer_name_id' => 'nullable|exists:sub_accounts,sub_account_id',
        'discount' => 'nullable|numeric|min:0',
        'date' => 'nullable|date', // تأكد من إضافة هذا الحقل
        'listRadio' => 'required|in:1,2',
        'sales_invoice_id' => 'nullable|numeric|min:0',
        'payment_type' => 'required|numeric',
        'financial_account_id' => 'required|numeric',
        'currency_id' => 'required|exists:currencies,currency_id',
        'exchange_rate' => 'nullable|numeric|min:0',
        'invoice_id' => 'nullable|numeric',
        'shipping_bearer' => 'required|in:customer,merchant',
        'transaction_type' => 'required|numeric',
        'note' => 'nullable',
        'shipping_amount' => 'nullable|numeric|min:0',
    ]);

    $today = Carbon::now()->toDateString();
    $dailyPage = GeneralJournal::whereDate('created_at', $today)->latest()->first();

    if (!$dailyPage) {
        $dailyPage = GeneralJournal::create([
            'accounting_period_id' => $accountingPeriod->accounting_period_id,
        ]);
    }

    if (!$dailyPage || !$dailyPage->page_id) {
        return response()->json(['success' => false, 'message' => 'فشل في إنشاء صفحة يومية']);
    }

    $transactiontype = TransactionType::fromValue($validatedData['transaction_type'])?->label();

    $saleInvoice = SaleInvoice::where('sales_invoice_id', $request->sales_invoice_id)
        ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
        ->first();

    if (!$saleInvoice) {
        return response()->json(['success' => false, 'message' => 'الفاتورة غير موجودة.'], 404);
    }

    if ($accountingPeriod->accounting_period_id !== $saleInvoice->accounting_period_id)
     {
        return response()->json(['success' => false, 'message' => 'فترة محاسبية مغلقة.']);
    }

    $net_total_after_discount = $this->calculateNetTotalAfterDiscount($saleInvoice, $validatedData) ?? 0;
    $discount = $validatedData['discount'] ?? $this->calculateDiscount($saleInvoice);
    $paid_amount = 0;
    $account_debit = null;
    $account_Credit = null;

    $updateSale = Sale::where('Invoice_id', $request->sales_invoice_id)->get();
    $DefaultCustomer = Default_customer::where('id', 1)->first();
    $warehouse_id = SubAccount::where('sub_account_id', $DefaultCustomer->warehouse_id)->value('sub_account_id'); // استخدام value بدلاً من pluck

    $entrie_id = DailyEntrie::where('Invoice_id', $request->sales_invoice_id)
        ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
        ->whereIn('daily_entries_type', ["مردود مبيعات", "مبيعات"])
        ->first();

    $Getentrie_id = $entrie_id->entrie_id ?? null;
    $daily_page_id = $entrie_id->Daily_page_id ?? $dailyPage->page_id;

    if ($request->transaction_type == 4) {
        $this->handleTransactionType4($saleInvoice, $validatedData, $DefaultCustomer, $warehouse_id, $net_total_after_discount, $Getentrie_id, $transactiontype, $daily_page_id, $updateSale);
    } elseif ($request->transaction_type == 5) {
        $this->handleTransactionType5($saleInvoice, $validatedData, $DefaultCustomer, $warehouse_id, $net_total_after_discount, $Getentrie_id, $transactiontype, $daily_page_id, $updateSale);
    }

    return response()->json([
        'success' => true,
        'message' => 'تم تحديث الفاتورة بنجاح.',
        'net_total_after_discount' => $saleInvoice->net_total_after_discount,
        'discount' => $saleInvoice->discount,
    ], 200);
}

private function calculateNetTotalAfterDiscount($saleInvoice, $validatedData)
{
    $total_price_sale = Sale::where('Invoice_id', $saleInvoice->sales_invoice_id)
        ->where('accounting_period_id', $saleInvoice->accounting_period_id)
        ->sum('total_amount');

    return $total_price_sale - ($validatedData['discount'] ?? 0);
}

private function calculateDiscount($saleInvoice)
{
    return Sale::where('Invoice_id', $saleInvoice->sales_invoice_id)
        ->where('accounting_period_id', $saleInvoice->accounting_period_id)
        ->sum('discount');
}

private function handleTransactionType4($saleInvoice, $validatedData, $DefaultCustomer, $warehouse_id, $net_total_after_discount, $Getentrie_id, $transactiontype, $daily_page_id, $updateSale)
{
    switch ($validatedData['payment_type']) {
        case 1:
        case 3:
        case 4:
            $account_debit = $validatedData['financial_account_id'];
            $account_Credit =$DefaultCustomer->warehouse_id; // قيمة مباشرة
            $account_debit = intval($account_debit);
            $paid_amount = $net_total_after_discount;
            break;

        case 2:
            $account_Credit = $DefaultCustomer->warehouse_id; // قيمة مباشرة
            $account_debit = $validatedData['Customer_name_id'];
            $account_debit = intval($account_debit);
            $paid_amount = $net_total_after_discount;
            break;

        default:
            // التعامل مع حالة غير متوقعة
            break;
    }

    $this->updateSales($updateSale, $validatedData, $DefaultCustomer);
    $this->saleInvoiceupdate($validatedData,$saleInvoice, $account_Credit, $account_debit, $net_total_after_discount, $Getentrie_id, $transactiontype, $daily_page_id,  $validatedData['payment_type']);
   
}

private function handleTransactionType5($saleInvoice, $validatedData, $DefaultCustomer, $warehouse_id, $net_total_after_discount, $Getentrie_id, $transactiontype, $daily_page_id, $updateSale)
{

    switch ($validatedData['payment_type']) {
        case 1:
        case 3:
        case 4:
            $account_Credit = $validatedData['financial_account_id'];
            $account_debit = $DefaultCustomer->warehouse_id; // قيمة مباشرة
            $account_debit = intval($account_debit);
            $paid_amount = $net_total_after_discount;
            break;

        case 2:
            $account_Credit = $validatedData['Customer_name_id'];
            $account_debit = $DefaultCustomer->warehouse_id;
            $account_debit = intval($account_debit);
            $paid_amount = $net_total_after_discount;
            break;

        default:
            // التعامل مع حالة غير متوقعة
            break;
    }

    $this->updateSales($updateSale, $validatedData, $DefaultCustomer);
    $this->saleInvoiceupdate($validatedData,$saleInvoice, $account_Credit, $account_debit, $net_total_after_discount, $Getentrie_id, $transactiontype, $daily_page_id,  $validatedData['payment_type']);
  
}

private function saleInvoiceupdate($validatedData,$saleInvoice, $account_Credit, $account_debit, $net_total_after_discount, $Getentrie_id, $transactiontype, $daily_page_id, $payment_type)
{ 
    if ($validatedData['listRadio'] == 2) {
   
    $date = Carbon::createFromFormat('Y-m-d', $validatedData['date']); // استخدام createFromFormat

    DB::table('sales_invoices')
    ->where('sales_invoice_id', $saleInvoice->sales_invoice_id)
    ->update(['created_at' => $date]);

}
    $saleInvoice->update([
        'Customer_id' => $validatedData['Customer_name_id'],
        'paid_amount' => $paid_amount ?? 0,
        'discount' => $validatedData['discount'] ?? 0,
        'shipping_amount' => $validatedData['shipping_amount'] ?? 0,
        'remaining_amount' => $net_total_after_discount - ($paid_amount ?? 0),
        'financial_account_id' => $validatedData['financial_account_id'],
        'payment_type' => $validatedData['payment_type'],
        'note' => $validatedData['note'],
        'account_id' => $validatedData['financial_account_id'],
        'currency_id' => $validatedData['currency_id'],
        'exchange_rate' => $validatedData['exchange_rate'] ?? 0,
        'transaction_type' => $validatedData['transaction_type'],
        'shipping_bearer' => $validatedData['shipping_bearer'] ?? 0,
    ]);
    $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
    if (!$accountingPeriod) {
        return response()->json(['success' => false, 'message' => 'لا توجد فترة محاسبية مفتوحة.']);
    }

    // التحقق من وجود الصفحة اليومية
    $today = Carbon::now()->toDateString();
    $dailyPage = GeneralJournal::whereDate('created_at', $today)->latest()->first();

    if (!$dailyPage) {
        $dailyPage = GeneralJournal::create([
            'accounting_period_id' => $accountingPeriod->accounting_period_id,
        ]);
    }

    if (!$dailyPage || !$dailyPage->page_id) 
    {
        return response()->json(['success' => false, 'message' => 'فشل في إنشاء صفحة يومية']);
    }
    $commint = $this->getComment($saleInvoice);
    $payment_type = intval($payment_type);
    // تحديث أو إنشاء القيد
    // try {
        if($payment_type==1)
        {
           $paymenttype="نقدا";
        }
        if($payment_type==2)
        {
           $paymenttype="اجل";
        }
        if($payment_type==3)
        {
           $paymenttype="تحويل بنكي";
        }
        if($payment_type==4)
        {
           $paymenttype="شيك";
        }
        if( $validatedData['note'])
        {
            $note="/".$validatedData['note'] ??'';

        }
        else
        {
            $note='';
        }
         
        $dailyEntrie = DailyEntrie::updateOrCreate(
            [
                'entrie_id' => $Getentrie_id,
                'accounting_period_id' => $accountingPeriod->accounting_period_id,
                'Invoice_id' => $saleInvoice->sales_invoice_id,
            ],
            [
                'daily_entries_type' => $transactiontype,
                'account_Credit_id' => $account_Credit,
                'created_at' => $saleInvoice->created_at,

                'account_debit_id' => $account_debit,
                'Amount_Credit' => $net_total_after_discount ?: 0,
                'Amount_debit' => $net_total_after_discount ?: 0,
                'Statement' => $commint . " " . $transactiontype . " " . $paymenttype.$note,
                'Daily_page_id' => $daily_page_id ?? $dailyPage->page_id,
                'Invoice_type' => $payment_type,
                'Currency_name' => 'ر',
                'User_id' => auth()->user()->id,
                'status_debit' => 'غير مرحل',
                'status' => 'غير مرحل',
            ]
        );
}

private function updateSales($updateSale, $validatedData, $DefaultCustomer)
{
    foreach ($updateSale as $sale) {
        $sale->update([
            'transaction_type' => $validatedData['transaction_type'],
            'warehouse_to_id' => $DefaultCustomer->warehouse_id,
            'Customer_id' => $validatedData['Customer_name_id'],
            'financial_account_id' => $validatedData['financial_account_id'],
        ]);
    }
}



private function getComment($saleInvoice)
{
    if ($saleInvoice->transaction_type == 4)
     {
    if (in_array($saleInvoice->payment_type, [1, 3, 4])) {
        return "فاتورة";
    } elseif ($saleInvoice->payment_type == 2) {
     
            return "عليكم فاتورة";
        }
    }
        if ($saleInvoice->transaction_type == 5) 
        {
            if (in_array($saleInvoice->payment_type, [1, 3, 4])) {
                return "فاتورة";
            } elseif ($saleInvoice->payment_type == 2) {
             
                    return "لكم فاتورة";
                }   
            
            }

}
public function getSaleInvoice(Request $request, $filterType)
{
    $validated = $request->validate([
      
        'fromDate' => 'nullable',
        'toDate' => 'nullable',
    ]);
    $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();

    if (!$accountingPeriod) {
        return response()->json(['message' => 'لم يتم العثور على فترة محاسبية حالية.'], 404);
    }

    $query = SaleInvoice::with(['customer.mainAccount', 'user'])
;
    switch ($filterType) {
        case '1':
            $query->where('accounting_period_id', $accountingPeriod->accounting_period_id);
            break;
        case '2':
            $query->where('accounting_period_id', $accountingPeriod->accounting_period_id);

            $query->whereDate('created_at', now()->toDateString());
            break;
        case '3':
            $query->where('accounting_period_id', $accountingPeriod->accounting_period_id);

            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            break;
        case '4':
            $query->where('accounting_period_id', $accountingPeriod->accounting_period_id);

            $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
            break;
        case '5':
         
     // الفترة المخصصة
     if ($request->filled(['fromDate', 'toDate'])) {

        $query->whereBetween('created_at', [$validated['fromDate'], $validated['toDate']]);
    }
            break;
          
    }

    
 $user=auth()->id();
 $AuthorityName="الفواتير المبيعات";
 $us=UserPermission::where('User_id', $user)
 ->where('Authority_Name',$AuthorityName)
 ->first();
 if (optional($us)->Readability ==1) {
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
            'shipping_amount' =>number_format($invoice->shipping_amount,2)  ?? 0,
            'total_price_sale' =>number_format( $invoice->total_price_sale,2)  ?? 0, 
            'net_total_after_discount' => $invoice->net_total_after_discount ?? 0,
            'paid_amount' => $invoice->paid_amount ?? 0,
            'remaining_amount' => $invoice->remaining_amount ?? 0,
            'user_name' => $invoice->userName ?? 'غير معروف',
            'updated_at' => optional($invoice->updated_at)->format('Y-m-d') ?? 'غير متاح',
            'view_url' => route('searchInvoices', $invoice->sales_invoice_id),
            'destroy_url' => route('sales-invoice.delete', $invoice->sales_invoice_id),
        ];

    });
 } else {

     return view('auth.login');
 }

   

    return response()->json(['saleInvoice' => $SaleInvoice]);
}


public function print(Request $request,$id)
{

    $validated = $request->validate([
      
        'analysis' => 'required|numeric',
    ]);
    // dd( $validated['analysis']);
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
    $saleInvoice = SaleInvoice::where('sales_invoice_id', $id)
        ->first();
        $note=$saleInvoice->note ?? '';

        $Categorys = Category::all();
        $curre=Currency::where('currency_id', $DataPurchaseInvoice->currency_id)->first();
        // حساب مجموع السعر والتكلفة
        
        $DataSale = Sale::where('Invoice_id', $id)->get();
        if ($DataSale->isEmpty()) {
            $Sale_priceSum = 0;
            $Sale_CostSum = 0;
        } else {
            $Sale_priceSum = $DataSale->sum('total_price');
            $Sale_CostSum = $DataSale->sum('total_amount');
            $total_Profit = $DataSale->sum('total_Profit');
        }
    // $Sale_CostSum = Sale::where('Invoice_id', $id)->sum('total_amount');
    $discount=   $Sale_CostSum -  $saleInvoice->net_total_after_discount ??0;
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
    $numberTransformer = $numberToWords->getNumberTransformer('ar');
  $numeric=is_numeric($Sale_priceSum- $saleInvoice->discount) 
    ? $numberTransformer->toWords($Sale_priceSum- $saleInvoice->discount) . ' ' . $curre->currency_name
    : 'القيمة غير صالحة';
    // اللغة العربية
    $thanks="شكراً لتعاملك معنا";
    // dd($numeric);
    $user=auth()->id();
    $AuthorityName="الفواتير المبيعات";
    $us=UserPermission::where('User_id', $user)
    ->where('Authority_Name',$AuthorityName)
    ->first();
    // Analytical-sales-invoice
    if (optional($us)->Readability == 1) {
if($validated['analysis']==1)
{
    return view('invoice_sales.bills_sale_show', [
        'DataPurchaseInvoice' => $DataPurchaseInvoice,
        'DataSale' => $DataSale,
        'SubAccounts' => $SubAccount,
        'Sale_priceSum' => $Sale_priceSum,
        'Sale_CostSum' => $Sale_CostSum,
        'priceInWords' => $numeric,
        'Categorys' => $Categorys,
        'currency' => $curre->currency_name,
        'payment_type' => PaymentType::tryFrom($DataPurchaseInvoice->payment_type)?->label() ?? 'غير معروف',
        'transaction_type' => TransactionType::fromValue($DataPurchaseInvoice->transaction_type)?->label() ?? 'غير معروف',
        'warehouses' => $SubName,
        'total_Profit' =>number_format($total_Profit,2)??0,
        'UserName' => $UserName,
        'accountCla' => $AccountClassName,
        'Sum_amount' => $Sum_amount,
        'thanks'=>$thanks,
        'note'=>$note ??'',
        'discount'=>$discount,

    ]);
}
       
if($validated['analysis']==2)
{
    return view('invoice_sales.Analytical-sales-invoice', [
        'DataPurchaseInvoice' => $DataPurchaseInvoice,
        'DataSale' => $DataSale,
        'SubAccounts' => $SubAccount,
        'Sale_priceSum' => $Sale_priceSum,
        'Sale_CostSum' => $Sale_CostSum,
        'priceInWords' => $numeric,
        'total_Profit' => $total_Profit??0,
        'Categorys' => $Categorys,
        'currency' => $curre->currency_name,
        'payment_type' => PaymentType::tryFrom($DataPurchaseInvoice->payment_type)?->label() ?? 'غير معروف',
        'transaction_type' => TransactionType::fromValue($DataPurchaseInvoice->transaction_type)?->label() ?? 'غير معروف',
        'warehouses' => $SubName,
        'UserName' => $UserName,
        'accountCla' => $AccountClassName,
        'Sum_amount' => $Sum_amount,
        'thanks'=>$thanks,
        'note'=>$note ??'',
        'discount'=>$discount,

    ]);
}
       
    } else {
        return view('auth.login');
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
    $query = SaleInvoice::with(['customer', 'user']);        if ($validated['searchQuery'] ?? false) {
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
        if ($request->filled(['fromDate', 'toDate'])) {

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
    // ترتيب الفواتير حسب نوع البحث
   
 // dd($numeric);

 $user=auth()->id();
 $AuthorityName="الفواتير المبيعات";
 $us=UserPermission::where('User_id', $user)
 ->where('Authority_Name',$AuthorityName)
 ->first();
 if (optional($us)->Readability == 1) {
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
 } else {
     return view('auth.login');
 }
    // الحصول على النتائج
   

    return response()->json(['saleInvoice' => $SaleInvoice]);
}





    }
