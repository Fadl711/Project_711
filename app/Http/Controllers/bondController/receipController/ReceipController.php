<?php

namespace App\Http\Controllers\bondController\receipController;

use App\Enum\PaymentType;
use App\Http\Controllers\Controller;
use App\Models\AccountingPeriod;
use App\Models\Currency;
use App\Models\DailyEntrie;
use App\Models\GeneralJournal;
use App\Models\MainAccount;
use App\Models\PaymentBond;
use App\Models\SubAccount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use NumberToWords\NumberToWords;

class ReceipController extends Controller
{
    public function create(){
        $mainAccount=MainAccount::all();
        $curr=Currency::all();
        // الحصول على تاريخ اليوم
        $today = Carbon::now()->toDateString(); // الحصول على تاريخ اليوم بصيغة YYYY-MM-DD
        $dailyPage = GeneralJournal::whereDate('created_at', $today)->first(); // البحث عن الصفحة
        return view('bonds.receipt_bonds.create',compact('curr','dailyPage'),['mainAccounts'=> $mainAccount,$dailyPage]);
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
$paymentBond = PaymentBond::updateOrCreate(
    [
        'payment_bond_id' => $payment_bond_id,
        'accounting_period_id' => $accountingPeriod->accounting_period_id,
        'created_at' => $request->date,
    ],
    [
        'Main_debit_account_id' => $request->AccountReceivable,
        'Debit_sub_account_id' => $request->DepositAccount,
        'Main_Credit_account_id' => $request->PaymentParty,
        'Credit_sub_account_id' => $request->CreditAmount,
        'payment_type' => $request->payment_type,
        'Currency_id' => $request->Currency,
        'Amount_debit' => $request->Amount_debit,
        'transaction_type' =>$request->transaction_type,
        'Statement' => $request->Statement ?? $request->transaction_type,
        'User_id' => $request->User_id,
    ]
);

        // الحصول على تاريخ اليوم بصيغة YYYY-MM-DD

        $today = Carbon::now()->toDateString();
        $dailyPage = GeneralJournal::whereDate('created_at', $today)->first();
        $curr=Currency::all();
        $curre=Currency::where('currency_id', $paymentBond->Currency_id)->pluck('currency_name')->first();

        // إذا لم توجد صفحة، قم بإنشائها
        if (!$dailyPage) {
            $dailyPage = GeneralJournal::create([]);
        }


        $Getentrie_id = DailyEntrie::where('Invoice_id', $paymentBond->payment_bond_id)
        ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
        ->where('daily_entries_type', $paymentBond->transaction_type)
        ->first();
    
    $entrie_id = $Getentrie_id->entrie_id ?? null;
    $daily_page_id = $Getentrie_id->Daily_page_id ?? $dailyPage->page_id;
    
    DailyEntrie::updateOrCreate(
        [
            'entrie_id' => $entrie_id,
            'accounting_period_id' => $accountingPeriod->accounting_period_id,
        ],
        [
            'daily_entries_type' => $paymentBond->transaction_type,
            'Invoice_id' => $paymentBond->payment_bond_id,
            'account_debit_id' => $paymentBond->Debit_sub_account_id,
            'Amount_Credit' => $paymentBond->Amount_debit ?: 0,
            'Amount_debit' => $paymentBond->Amount_debit ?: 0,
            'account_Credit_id' => $paymentBond->Credit_sub_account_id,
            'Statement' => $paymentBond->Statement,
            'Daily_page_id' => $daily_page_id,
            'Invoice_type' => $paymentBond->payment_type,
            'Currency_name' => $curre,
            'User_id' => auth()->user()->id,
            'status_debit' => 'غير مرحل',
            'status' => 'غير مرحل',
        ]
    );
  
    
    return response()->json([
        'success' => 'تم بنجاح',
        'payment_bond_id' => $paymentBond->payment_bond_id ?? $payment_bond_id
    ]);
    
    // return redirect()->route('Receip.create', ['id' => $paymentBond->payment_bond_id??$payment_bond_id])->with('success', 'تم تعديل السند بنجاح');


    }
    public function show($id){

        $PaymentBond=PaymentBond::where('payment_bond_id',$id)->first();
        return view('bonds.receipt_bonds.show',compact('PaymentBond'));
    }
        public function edit($id){
            $ExchangeBond=PaymentBond::where('payment_bond_id',$id)->first();
            $mainAccount=MainAccount::all();
            $SubAccounts=SubAccount::all();
            $Debitsub_account_id=SubAccount::where('sub_account_id',$ExchangeBond->Debit_sub_account_id)->first();
            $Creditsub_account_id=SubAccount::where('sub_account_id',$ExchangeBond->Credit_sub_account_id)->first();
            $submitButton="تعديل السند";
            $currs=Currency::where('currency_id',$ExchangeBond->Currency_id)->first();
            // الحصول على تاريخ اليوم
            $today = Carbon::now()->toDateString(); // الحصول على تاريخ اليوم بصيغة YYYY-MM-DD
            $dailyPage = GeneralJournal::whereDate('created_at',$today)->first(); // البحث عن الصفحة
          
            return view('bonds.receipt_bonds.create', [
                'ExchangeBond' => $ExchangeBond,
                'Debitsub_account_id' => $Debitsub_account_id,
                'Creditsub_account_id' => $Creditsub_account_id,
                'currs' => $currs,
                'mainAccounts' => $mainAccount,
                'SubAccounts' => $SubAccounts,
                'dailyPage' => $dailyPage,
                'submitButton' => 'تعديل السند',
            ]);  

        }
   
        public function destroy($id)
        {
            $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
            if (!$accountingPeriod) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'لا توجد فترة محاسبية مفتوحة.',
                ], 400);
            }
        
            // الحصول على سند الدفع
            $paymentBond = PaymentBond::find($id);
            if (!$paymentBond) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'سند الدفع غير موجود.',
                ], 404);
            }
        
            try {
                // حذف سند الدفع
                $paymentBond->delete();
        
                // التحقق من وجود قيود يومية مرتبطة
                $dailyEntry = DailyEntrie::where('Invoice_id', $paymentBond->payment_bond_id)
                    ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
                    ->where('daily_entries_type', $paymentBond->transaction_type)
                    ->first();
        
                if ($dailyEntry) {
                    $dailyEntry->delete();
                }
        
                return response()->json([
                    'status' => 'success',
                    'message' => 'تم حذف سند الدفع والقيود المرتبطة بنجاح.',
                ], 200);
        
            } catch (\Exception $e) {
                // تسجيل الخطأ لمراجعة لاحقًا
                \Log::error('Error deleting payment bond: ' . $e->getMessage());
        
                return response()->json([
                    'status' => 'error',
                    'message' => 'حدث خطأ أثناء الحذف. يرجى المحاولة لاحقًا.',
                ], 500);
            }
        }
        
        
public function print($id){
    $PaymentBond = PaymentBond::where('payment_bond_id', $id)->first();
    $currs=Currency::where('currency_id',$PaymentBond->Currency_id)->first();
    if( $PaymentBond->payment_type===1)
    {
        $payment_type="نقداً";
    }
    if( $PaymentBond->payment_type===2)
    {
        $payment_type="أجل";
    }
    if( $PaymentBond->payment_type===3)
    {
        $payment_type="تحويل بنكي";
    }
    if( $PaymentBond->payment_type===4)
    {
        $payment_type="شيك";
    }
   
    $currency = $currs->currency_name;
// جلب البيانات وتحويلها
$numberToWords = new NumberToWords();
$numberTransformer = $numberToWords->getNumberTransformer('ar'); // اللغة العربية
$result = is_numeric($PaymentBond->Amount_debit) 
? $numberTransformer->toWords($PaymentBond->Amount_debit) . ' ' . $currency
: 'القيمة غير صالحة';
    // استخدام $currency بشكل صحيح
    return view('bonds.receipt_bonds.print', compact('payment_type','PaymentBond', 'result'));
}


public function getPaymentBond(Request $request, $filterType)
{
      // التحقق من المدخلات
       $validated = $request->validate([
        'searchType' => 'nullable|string|in:كل السندات,أول سند,آخر سند',
        'transactionType' => 'nullable|string',
        'searchQuery' => 'nullable|string|max:255',
    ]);
    
    // الحصول على آخر فترة محاسبية نشطة
    $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();

    if (!$accountingPeriod) {
        return response()->json(['message' => 'لم يتم العثور على فترة محاسبية حالية.'], 404);
    }

    // إنشاء استعلام السندات
    $query = PaymentBond::with(['creditSubAccount', 'debitSubAccount', 'user'])
    ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
    ->where('transaction_type',  $validated['transactionType'])
;
  // فلترة بناءً على نوع السند
  // فلترة بناءً على نوع السند
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
    
    $PaymentInvoices = $query->get()->map(function ($invoice) use ($numberTransformer) {
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
            'view_url' => route('receip.show', $invoice->payment_bond_id),
            'edit_url' => route('receip.edit', $invoice->payment_bond_id),
            'destroy_url' => route('receip_destroy.destroy', $invoice->payment_bond_id),
        ];
    });
    
    return response()->json(['PaymentInvoices' => $PaymentInvoices], 200);
}

public function searchInvoices(Request $request)
{
    // التحقق من المدخلات
    $validated = $request->validate([
        'searchType' => 'nullable|string|in:كل السندات,أول سند,آخر سند',
        'transactionType' => 'nullable|string',
        'searchQuery' => 'nullable|string|max:255',
    ]);

    // التحقق من وجود transactionType
    if (empty($validated['transactionType'])) {
        return response()->json(['message' => 'transactionType is required']);
    }

    // الحصول على آخر فترة محاسبية نشطة
    $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();

    if (!$accountingPeriod) {
        return response()->json(['message' => 'لم يتم العثور على فترة محاسبية حالية.'], 404);
    }

    // إنشاء استعلام السندات
    $query = PaymentBond::with(['creditSubAccount', 'debitSubAccount', 'user'])
        ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
        ->where('transaction_type', $validated['transactionType']);

    // التحقق من وجود searchQuery وتطبيقه
    if (isset($validated['searchQuery']) && !empty($validated['searchQuery'])) {
        $searchQuery = $validated['searchQuery'];

        $query->where(function ($query) use ($searchQuery) {
            // البحث باستخدام رقم الفاتورة
            $query->where('payment_bond_id', 'like', $searchQuery . '%')
                // البحث باستخدام اسم المورد
                ->orWhereHas('debitSubAccount', function ($query) use ($searchQuery) {
                    $query->where('sub_name', 'like', $searchQuery . '%');
                });
        });
    }

    // ترتيب الفواتير حسب نوع البحث
    if (isset($validated['searchType']) && $validated['searchType'] !== 'كل السندات') {
        $orderDirection = ($validated['searchType'] === 'أول سند') ? 'asc' : 'desc';
        $query->orderBy('created_at', $orderDirection);
    }

    // جلب البيانات وتحويلها
    $numberToWords = new NumberToWords();
    $numberTransformer = $numberToWords->getNumberTransformer('ar'); // اللغة العربية

    $PaymentInvoices = $query->get()->map(function ($invoice) use ($numberTransformer) {
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

    return response()->json(['PaymentInvoices' => $PaymentInvoices], 200);
}

}

