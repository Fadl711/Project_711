<?php

namespace App\Http\Controllers\bondController\exchangeController;

use App\Enum\PaymentType;
use App\Http\Controllers\Controller;
use App\Models\AccountingPeriod;
use App\Models\Currency;
use App\Models\DailyEntrie;
use App\Models\ExchangeBond;
use App\Models\GeneralJournal;
use App\Models\MainAccount;
use App\Models\PaymentBond;
use App\Models\SubAccount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use NumberToWords\NumberToWords;

class ExchangeController extends Controller
{
    public function create(){
        return view('bonds.receipt_bonds.index');
    }
    public function index(){
        $mainAccount=MainAccount::all();
        $curr=Currency::all();
        // الحصول على تاريخ اليوم
        $today = Carbon::now()->toDateString(); // الحصول على تاريخ اليوم بصيغة YYYY-MM-DD
        $dailyPage = GeneralJournal::whereDate('created_at', $today)->first(); // البحث عن الصفحة
        return view('bonds.exchange_bonds.index',compact('curr','dailyPage'),['mainAccounts'=> $mainAccount,$dailyPage]);
     }
     
     public function store(Request $request){
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();

if (!$accountingPeriod) {
    return response()->json(['error' => 'لا توجد فترة محاسبية مفتوحة']);
}

if ($request->AccountReceivable == $request->PaymentParty) {
if ($request->DepositAccount == $request->CreditAmount) {
    return response()->json(['error' => 'لايمكن اختيار نفس الحساب']);
}
}

$payment_bond_id = $request->payment_bond_id;
$paymentBond = ExchangeBond::updateOrCreate(
    [
        'payment_bond_id' => $payment_bond_id,
    ],
    [
        'Main_debit_account_id' => $request->AccountReceivable,
        'Debit_sub_account_id' => $request->DepositAccount,
        'Main_Credit_account_id' => $request->PaymentParty,
        'Credit_sub_account_id' => $request->CreditAmount,
        'payment_type' => $request->payment_type,
        'accounting_period_id' => $accountingPeriod->accounting_period_id,
        'Currency_id' => $request->Currency,
        'Amount_debit' => $request->Amount_debit,
        'transaction_type' => 'سند صرف',
        'Statement' => $request->Statement ?? "سند صرف",
        'User_id' => $request->User_id,
        'created_at' => $request->date,
    ]
);

if (!$paymentBond->wasRecentlyCreated && !$paymentBond->wasChanged()) {
    return response()->json(['error' => 'لم يتم حفظ البيانات']);
}


        // الحصول على تاريخ اليوم بصيغة YYYY-MM-DD
        $today = Carbon::now()->toDateString();
        $dailyPage = GeneralJournal::whereDate('created_at', $today)->first();

        // إذا لم توجد صفحة، قم بإنشائها
        if (!$dailyPage) {
            $dailyPage = GeneralJournal::create([]);
        }


        $Getentrie_id = DailyEntrie::where('Invoice_id', $paymentBond->payment_bond_id)
            ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
            ->where('daily_entries_type',$paymentBond->transaction_type)
            ->value('entrie_id');
    
        // Create or update the daily entry
        DailyEntrie::updateOrCreate(
            [
                'entrie_id' => $Getentrie_id,
                'accounting_period_id' => $accountingPeriod->accounting_period_id,
            ],
            [
                'daily_entries_type' => $paymentBond->transaction_type,
                'Invoice_id' => $paymentBond->payment_bond_id,
                'account_debit_id' => $paymentBond->Debit_sub_account_id,
                'Amount_Credit' => $paymentBond->Amount_debit ?: 0,
                'Amount_debit' => $paymentBond->Amount_debit ?: 0,
                'account_Credit_id' => $paymentBond->Credit_sub_account_id,
                'Statement' =>  $paymentBond->Statement,
                'Daily_page_id' => $dailyPage->page_id,
                'Invoice_type' =>$paymentBond->payment_type,
                'Currency_name' => 'ر',
                'User_id' => auth()->user()->id,
                'status_debit' => 'غير مرحل',
                'status' => 'غير مرحل',
            ]
        );
       


        return response()->json(['success' => 'تم بنجاح']);

    }

    
public function getPaymentBond(Request $request, $filterType)
{
      // التحقق من المدخلات
       $validated = $request->validate([
        'searchType' => 'nullable|string|in:كل السندات,أول سند,آخر سند',
        'transaction_type' => 'nullable|string|in: سند قبض,سند صرف ',
        'searchQuery' => 'nullable|string|max:255',
    ]);
    // بناء الاستعلام الأساس
    // الحصول على آخر فترة محاسبية نشطة
    $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();

    if (!$accountingPeriod) {
        return response()->json(['message' => 'لم يتم العثور على فترة محاسبية حالية.'], 404);
    }
    $transaction_type="سند صرف";

    // إنشاء استعلام السندات
    $query = PaymentBond::with(['creditSubAccount', 'debitSubAccount', 'user'])
    ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
    ->where('transaction_type', $transaction_type)
;
  // فلترة بناءً على نوع السند
  // فلترة بناءً على نوع السند
  if ($request->filled('transaction_type')) {
    $query->where('transaction_type', $validated["transaction_type"]);
}
    // تطبيق الفلترة بناءً على نوع الفلترة (تواريخ)
    switch ($filterType) {
       
       
        case '2': // اليوم
            $query->whereDate('created_at', now()->toDateString());
            break;
        case '3': // هذا الأسبوع
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            break;
        case '4': // هذا الشهر
            $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
            break;
        default: // الفترة المخصصة
            if ($request->filled(['fromDate', 'toDate'])) {
                $query->whereBetween('created_at', [$request->fromDate, $request->toDate]);
            }
            break;
    }

   
    // جلب البيانات وتحويلها
    $numberToWords = new NumberToWords();
    $numberTransformer = $numberToWords->getNumberTransformer('ar'); // اللغة العربية
    
    $purchaseInvoices = $query->get()->map(function ($invoice) use ($numberTransformer) {
        $currency = $invoice->currency->currency_name ?? 'ريال'; // تحديد العملة
        return [
            'payment_bond_id' => $invoice->payment_bond_id,
            'formatted_date' => $invoice->formatted_date, // استخدام Accessor
            'sub_name_debit' => optional($invoice->debitSubAccount)->sub_name ?? 'غير معروف',
            'sub_name_credit' => optional($invoice->creditSubAccount)->sub_name ?? 'غير معروف',
            'payment_type' => PaymentType::tryFrom($invoice->payment_type)?->label() ?? 'غير معروف',
            'transaction_type' => $invoice->transaction_type ?? 'غير متاح',
            'amount_debit' => number_format($invoice->Amount_debit, 2),
            'result' => is_numeric($invoice->Amount_debit) 
                        ? $numberTransformer->toWords($invoice->Amount_debit) . ' ' . $currency
                        : 'القيمة غير صالحة',
            'statement' => $invoice->Statement ?? 'غير متاح',
            'user_name' => $invoice->userName, // استخدام Accessor
            'updated_at' => optional($invoice->updated_at)->format('Y-m-d') ?? 'غير متاح',
        ];
    });
    
    return response()->json(['purchaseInvoices' => $purchaseInvoices], 200);
}

     public function all_exchange_bonds(){
        // $PaymentBonds=ExchangeBond::all();
        $SubAccounts=SubAccount::all();
       $MainAccounts= MainAccount::all();
       $Currencies=Currency::all();
        return view('bonds.exchange_bonds.all_exchange_bonds',compact('SubAccounts','MainAccounts','Currencies'));
     }
     public function show($id){

        $PaymentBond=ExchangeBond::where('payment_bond_id',$id)->first();
        return view('bonds.exchange_bonds.show',compact('PaymentBond'));
    }
    public function edit($id){
        $ExchangeBond=ExchangeBond::where('payment_bond_id',$id)->first();
        $mainAccount=MainAccount::all();
        $SubAccounts=SubAccount::all();
        $curr=Currency::all();
        // الحصول على تاريخ اليوم
        $today = Carbon::now()->toDateString(); // الحصول على تاريخ اليوم بصيغة YYYY-MM-DD
        $dailyPage = GeneralJournal::whereDate('created_at', $today)->first(); // البحث عن الصفحة
        $ExchangeBond=ExchangeBond::where('payment_bond_id',$id)->first();
        return view('bonds.exchange_bonds.edit',compact('curr','dailyPage','ExchangeBond','SubAccounts'),['mainAccounts'=> $mainAccount,$dailyPage]);
    }
    public function update(Request $request){
        $today = Carbon::now()->toDateString();
        $dailyPage = GeneralJournal::whereDate('created_at', $today)->first();
        $PaymentBond=ExchangeBond::where('payment_bond_id',$request->id)->first();
        $Currency=Currency::where('currency_id',$request->Currency)->value('currency_name');
        ExchangeBond::where('payment_bond_id',$request->id)->update([
            'Main_debit_account_id'=>$request->AccountReceivable,
            'Debit_sub_account_id'=>$request->DepositAccount,
            'Amount_debit'=>$request->Amount_debit,
            'Main_Credit_account_id'=>$request->PaymentParty,
            'Credit_sub_account_id'=>$request->CreditAmount,
            'Statement'=>$request->Statement,
            'Currency_id'=>$request->Currency,
            'User_id'=>$request->User_id,
            'created_at'=>$request->date,
        ]);
        DailyEntrie::where('updated_at',$PaymentBond->updated_at)->update([
            'account_debit_id'=>$request['DepositAccount'],
            'Amount_debit'=>$request['Amount_debit'],
            'account_Credit_id'=>$request['CreditAmount'],
            'Amount_Credit'=>$request['Amount_debit'],
            'Statement'=> $request['Statement'],
            'Currency_name'=>$Currency,
            'Daily_page_id'=>$dailyPage->page_id,
            'User_id'=>$request['User_id'],
        ]);

        return redirect()->route('all_exchange_bonds');
    }
    public function destroy($id){
        $ExchangeBond=ExchangeBond::where('payment_bond_id',$id)->first();

        DailyEntrie::where('updated_at',$ExchangeBond->updated_at)->delete();
        ExchangeBond::where('payment_bond_id',$id)->delete();

        return redirect()->route('all_exchange_bonds');
}
public function print($id){
    $PaymentBond=ExchangeBond::where('payment_bond_id',$id)->first();
    return view('bonds.exchange_bonds.print',compact('PaymentBond'));
}

public function stor(Request $request){
    $today = Carbon::now()->toDateString(); // الحصول على تاريخ اليوم بصيغة YYYY-MM-DD
    $dailyPage = GeneralJournal::whereDate('created_at', $today)->first(); // البحث عن الصفحة
    if ($dailyPage) {
            $generalJournal1=GeneralJournal::all();
            $mainAccount=MainAccount::all();
        $curr=Currency::all();
        return view('bonds.exchange_bonds.index',compact('curr','dailyPage'),['mainAccounts'=> $mainAccount]);
    } else {
        $Statement=$request->Statement;
             GeneralJournal::create([
            ]);
            // الحصول على تاريخ اليوم بصيغة YYYY-MM-DD
            $today = Carbon::now()->toDateString();
            $dailyPage = GeneralJournal::whereDate('created_at', $today)->first(); // البحث عن الصفحة
            if ($dailyPage) {
                // إذا تم العثور على الصفحة، عرض رقم الصفحة
                $generalJournal1=GeneralJournal::all();
                $mainAccount=MainAccount::all();
            // dd($generalJournal1);
            $curr=Currency::all();
            return view('bonds.exchange_bonds.index',compact('curr','dailyPage'),['mainAccounts'=> $mainAccount]);
            }
    }

}

}
