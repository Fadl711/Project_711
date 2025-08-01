<?php

namespace App\Http\Controllers\DailyRestrictionController;

use App\Enum\PaymentType;
use App\Enum\TransactionType;
use App\Http\Controllers\Controller;
use App\Models\AccountingPeriod;
use App\Models\Currency;
use App\Models\CurrencyConversion;
use App\Models\DailyEntrie;
use App\Models\ExchangeBond;
use App\Models\GeneralEntrie;
use App\Models\GeneralJournal;
use App\Models\MainAccount;
use App\Models\Operation;
use App\Models\PaymentBond;
use App\Models\PurchaseInvoice;
use App\Models\SaleInvoice;
use App\Models\SubAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class RestrictionController extends Controller
{
    //
    private function removeCommas($value)
    {
        return floatval(str_replace(',', '', $value)); // إزالة الفواصل وتحويل إلى float
    }
    // تحقق من صحة البيانات
    public function store(Request $request)
    {
        $user = Auth::user();
        if (! $user->canWrite('القيود')) {
            // return response()->json(['success'=>false,'errorMessage' => 'غير مصرح لك.']);

            abort(403, 'غير مصرح لك.');
        }
        $dailyPageId = DailyEntrie::where('entrie_id', $request->entrie_id)->first();
        if ($dailyPageId) {
            if (! $user->canModify('القيود')) {
                return response()->json(['success' => false, 'errorMessage' => 'غير مصرح لك.']);

                abort(403, 'غير مصرح لك.');
            }
            if ($dailyPageId->daily_entries_type == "رصيد افتتاحي") {
                return response()->json(['success' => false, 'errorMessage' => 'لا يمكنك تعديل الرصيد الافتتاحي من هنا يمكنك التعديل علية من صفحة الحسابات الفرعية']);
            }
            if ($dailyPageId->daily_entries_type == "سند صرف" || $dailyPageId->daily_entries_type == "سند قبض") {
                return response()->json(['success' => false, 'errorMessage' => 'لا يمكنك تعديل من هنا']);
            }
            $message = "إيداع في حساب : " . $dailyPageId->debitAccount->sub_name . " مبلغ وقدره : " . $dailyPageId->amount_debit . " تقيد المبلغ في حساب : " . $dailyPageId->creditAccount->sub_name;

            Operation::createOpertion($dailyPageId->entrie_id, 'تعديل', $dailyPageId->getTranslatedType(), $message);
        }
        $Amount_debit = $this->removeCommas($request->Amount_debit);
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
        $validated = $request->validate([
            'sub_account_debit_id' => 'required|integer',
            'Amount_debit' => 'required',
            'sub_account_Credit_id' => 'required|integer',
            'Statement' => 'nullable|string',
            'Currency_name' =>  'nullable|string', // تأكد من استخدام الاسم الصحيح هنا
            'User_id' => 'required|integer',
            'exchange_rate' => 'required',
        ]);
        // التأكد من عدم اختيار حسابين فرعيين متماثلين
        if ($request->sub_account_debit_id == $request->sub_account_Credit_id) {
            return response()->json(['success' => 'يجب عدم تساوي الحسابات الفرعية المدين والدائن.']);
        }
        if (!$dailyPageId) {
            // الحصول على تاريخ اليوم بصيغة YYYY-MM-DD
            $today = Carbon::now()->toDateString();
            $dailyPage = GeneralJournal::whereDate('created_at', $today)->latest()->first();


            // إذا كنت بحاجة لإنشاء سجل جديد في حال عدم وجود سجلات على الإطلاق
            if (!$dailyPage) {
                $dailyPage = GeneralJournal::create([
                    'accounting_period_id' => $accountingPeriod->accounting_period_id,
                ]);
            }
        }

        // حفظ القيد اليومي
        // $dailyEntrie = new DailyEntrie();
        if ($request->Invoice_type) {
            $transactionType = TransactionType::fromValue($request->Invoice_type);
            if ($transactionType) {
                $invoice_type = $transactionType->label(); // جلب التسمية النصية
            } else {

                throw new InvalidArgumentException('نوع الفاتورة غير معروف.');
            }
        }
        // تحديد النوع الافتراضي
        $defaultPaymentType = 'قيد';
        $Invoice_id = null;
        $Payment_type = $defaultPaymentType;
        if ($request->Invoice_type) {
            // التحقق من نوع المعاملة
            if (in_array($request->Invoice_type, [4, 5])) {
                // استرجاع فواتير المبيعات
                $invoices = SaleInvoice::where('sales_invoice_id', $request->Invoice_id)
                    ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
                    ->where('transaction_type', $request->Invoice_type)
                    ->first();
            } elseif (in_array($request->Invoice_type, [1, 2, 3])) {
                // استرجاع فواتير المشتريات
                $invoices = PurchaseInvoice::where('purchase_invoice_id', $request->Invoice_id)
                    ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
                    ->where('transaction_type', $request->Invoice_type)
                    ->first();
            }
            $Invoice_id = $request->Invoice_type >= 4 ? $invoices->sales_invoice_id : $invoices->purchase_invoice_id;
            $Payment_type = $request->Invoice_type >= 4 ? $invoices->payment_type : $invoices->Invoice_type;

            // التحقق من وجود الفاتورة
            if (!$invoices) {
                throw new \Exception('الفاتورة غير موجودة.');
            }
        }


        // // إنشاء القيد اليومي
        $dailyEntrie = DailyEntrie::updateOrCreate(
            [
                'entrie_id' => $request->entrie_id,
                'accounting_period_id' => $accountingPeriod->accounting_period_id,

            ],
            [
                'invoice_id' => $Invoice_id ?? null,
                'daily_page_id' => $dailyPage->page_id ?? $dailyPageId->daily_page_id,
                'daily_entries_type' => $invoice_type ?? $Payment_type,
                'account_debit_id' => $validated['sub_account_debit_id'],
                'amount_credit' => $Amount_debit,
                'amount_debit' =>  $Amount_debit,
                'account_credit_id' => $validated['sub_account_Credit_id'],
                'exchange_rate' => $validated['exchange_rate'],
                'statement' => $validated['Statement']  ?? "قيد يومي",
                'invoice_type' => $request->payment_type,
                'currency_name' => $validated['Currency_name'],
                'user_id' => $validated['User_id'],
                'status_debit' => 'غير مرحل',
                'status' => 'غير مرحل',
            ]
        );

        return response()->json(['success' => 'تم حفظ القيد بنجاح', 'entrie_id' => $dailyEntrie->entrie_id]);
    }
    public function storeCurrency(Request $request)
    {
        // dd(5);
        $user = Auth::user();
        if (! $user->canWrite('القيود')) {
            // return response()->json(['success'=>false,'errorMessage' => 'غير مصرح لك.']);

            abort(403, 'غير مصرح لك.');
        }
        if (!$request->Conversion_id) {
            $currentCondition = CurrencyConversion::create([
                'user_id' => $user->id,
            ]);
            $currentConditions = $currentCondition->id;
        } else {
            $currentConditions = $request->Conversion_id;
        }


        $Amount_debit = $this->removeCommas($request->Amount_debit);
        $Amount_credit = $this->removeCommas($request->Amount_credit);


        // الحصول على تاريخ اليوم بصيغة YYYY-MM-DD
        $today = Carbon::now()->toDateString();
        $dailyPage = GeneralJournal::whereDate('created_at', $today)->latest()->first();

        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();

        // إذا كنت بحاجة لإنشاء سجل جديد في حال عدم وجود سجلات على الإطلاق
        if (!$dailyPage) {
            $dailyPage = GeneralJournal::create([
                'accounting_period_id' => $accountingPeriod->accounting_period_id,
            ]);
        }



        $dataDebit = $request->validate([
            'sub_account_debit_id' => 'required|integer',
            'entrie_id_debit' => 'nullable|integer',
            'Amount_debit' => 'required',
            'Statement' => 'nullable|string',
            'Currency_name' =>  'nullable|string', // تأكد من استخدام الاسم الصحيح هنا
            'User_id' => 'required|integer',
            'exchange_rate' => 'required',
        ]);
        $dataCredit = $request->validate([
            'sub_account_debit_id' => 'required|integer',
            'entrie_id_credit' => 'nullable|integer',
            'Amount_credit' => 'required',
            'Statement' => 'nullable|string',
            'Currency_name_credit' =>  'nullable|string', // تأكد من استخدام الاسم الصحيح هنا
            'User_id' => 'required|integer',
            'exchange_rate_credit' => 'required',
        ]);
        $Amountcredit = $Amount_credit;
        $Amountdebit = $Amount_debit;
        $this->storeConversion($dataDebit, $Amount_debit, $Amount_credit = 0, $dailyPage, $request, $currentConditions, $entrie_id = $dataDebit["entrie_id_debit"]);
        $this->storeConversion($dataCredit, $Amount_debit = 0, $Amount_credit = $Amountcredit, $dailyPage, $request, $currentConditions, $entrie_id = $dataCredit["entrie_id_credit"]);
        return response()->json(['success' => 'تم حفظ القيد بنجاح']);
    }
    public function storeConversion($validated, $Amount_debit, $Amount_credit, $dailyPage, $request, $currentConditions, $entrie_id)
    {
        $user = Auth::user();
        if (! $user->canWrite('القيود')) {
            // return response()->json(['success'=>false,'errorMessage' => 'غير مصرح لك.']);

            abort(403, 'غير مصرح لك.');
        }

        // حفظ القيد اليومي
        // $dailyEntrie = new DailyEntrie();
        if ($request->Invoice_type) {
            $transactionType = TransactionType::fromValue($request->Invoice_type);
            if ($transactionType) {
                $invoice_type = $transactionType->label(); // جلب التسمية النصية
            } else {

                throw new InvalidArgumentException('نوع الفاتورة غير معروف.');
            }
        }
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();

        // تحديد النوع الافتراضي
        $defaultPaymentType = 'تحويل عملة';
        $Invoice_id = null;
        $Payment_type = $defaultPaymentType;
        // // إنشاء القيد اليومي
        $dailyEntrie = DailyEntrie::updateOrCreate(
            [
                'entrie_id' => $entrie_id,
                'accounting_period_id' => $accountingPeriod->accounting_period_id,

            ],
            [
                'invoice_id' => $currentConditions,
                'daily_page_id' => $request->page_id ?? $dailyPage->page_id,
                'daily_entries_type' => $invoice_type ?? $Payment_type,
                'account_debit_id' => $validated['sub_account_debit_id'],
                'amount_credit' => $Amount_credit,
                'amount_debit' =>  $Amount_debit,
                'account_credit_id' => $validated['sub_account_debit_id'],
                'exchange_rate' => $validated['exchange_rate'] ?? $validated['exchange_rate_credit'],
                'statement' => $validated['Statement']  ?? $invoice_type,
                'invoice_type' =>  null,
                'currency_name' => $validated['Currency_name'] ?? $validated['Currency_name_credit'],
                'user_id' => $validated['User_id'],
                'status_debit' => 'غير مرحل',
                'status' => 'غير مرحل',
            ]
        );
    }
    public function saveAndPrint(Request $request) {}
    public function stor(Request $request)
    {
        // dd(65)
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
        if (!$accountingPeriod) {
            return response()->json(['success' => false, 'message' => 'لا توجد فترة محاسبية مفتوحة.']);
        }
        $PaymentType = PaymentType::cases();
        $transaction_types = TransactionType::cases();
        $today = Carbon::now()->toDateString(); // الحصول على تاريخ اليوم بصيغة YYYY-MM-DD
        $dailyPage = GeneralJournal::whereDate('created_at', $today)->first(); // البحث عن الصفحة
        if ($dailyPage) {
            $generalJournal1 = GeneralJournal::where('accounting_period_id', $accountingPeriod->accounting_period_id)->get();
            $mainAccount = MainAccount::all();
            $curr = Currency::all();

            return view('daily_restrictions.create', compact('curr', 'dailyPage'), [
                'mainAccounts' => $mainAccount,
                'transaction_types' => $transaction_types,
                'PaymentType' => $PaymentType,
            ]);
        } else {
            $Statement = $request->Statement;
            GeneralJournal::create([
                'accounting_period_id' => $accountingPeriod->accounting_period_id,
            ]);
            // الحصول على تاريخ اليوم بصيغة YYYY-MM-DD
            $today = Carbon::now()->toDateString();
            $dailyPage = GeneralJournal::whereDate('created_at', $today)->first(); // البحث عن الصفحة
            if ($dailyPage) {
                // إذا تم العثور على الصفحة، عرض رقم الصفحة
                $generalJournal1 = GeneralJournal::where('accounting_period_id', $accountingPeriod->accounting_period_id)->get();
                $main_accounts = MainAccount::all();
                $curr = Currency::all();
                return view('daily_restrictions.create', compact('curr', 'dailyPage'), [
                    'main_accounts' => $main_accounts,
                    'transaction_types' => $transaction_types,
                    'PaymentType' => $PaymentType,
                ]);
            }
        }
    }

    public function create()
    {
        $user = Auth::user();
        if (! $user->hasPermission('القيود')) {
            abort(403, 'غير مصرح لك بعرض الصفحة.');
        }
        if (! $user->canWrite('القيود')) {
            abort(403, 'غير مصرح لك  .');
        }

        $main_accounts = MainAccount::all();
        $curr = Currency::all();
        $PaymentType = PaymentType::cases();
        $transaction_types = TransactionType::cases();


        // الحصول على تاريخ اليوم
        $today = Carbon::now()->toDateString(); // الحصول على تاريخ اليوم بصيغة YYYY-MM-DD
        $dailyPage = GeneralJournal::whereDate('created_at', $today)->first(); // البحث عن الصفحة
        return view('daily_restrictions.create', compact('curr', 'dailyPage'), [
            'main_accounts' => $main_accounts,
            'transaction_types' => $transaction_types,
            $dailyPage,
            'PaymentType' => $PaymentType
        ]);
    }
    public function createCurrency()
    {
        $user = Auth::user();
        if (! $user->hasPermission('القيود')) {
            abort(403, 'غير مصرح لك بعرض الصفحة.');
        }
        if (! $user->canWrite('القيود')) {
            abort(403, 'غير مصرح لك  .');
        }
        $transaction_types = TransactionType::cases();


        $curr = Currency::all();
        $mainAccount = MainAccount::all();
        $PaymentType = PaymentType::cases();

        // الحصول على تاريخ اليوم
        $today = Carbon::now()->toDateString(); // الحصول على تاريخ اليوم بصيغة YYYY-MM-DD
        $dailyPage = GeneralJournal::whereDate('created_at', $today)->first(); // البحث عن الصفحة
        return view('daily_restrictions.create-currency', compact('curr', 'dailyPage'), [
            'mainAccounts' => $mainAccount,
            $dailyPage,
            'transaction_types' => $transaction_types,
            'PaymentType' => $PaymentType,
        ]);
    }

    public function index()
    {
        $user = Auth::user();
        if (! $user->hasPermission('القيود')) {
            abort(403, 'غير مصرح لك بعرض الصفحة.');
        }
        return view('daily_restrictions.index');
    }

    public function pages()
    {
        $user = Auth::user();
        if (! $user->canRead('القيود')) {
            abort(403, 'غير مصرح لك بعرض القيد.');
        }
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();

        // $pageNums=GeneralJournal::all();
        $pageNums = GeneralJournal::with('dailyEntries')->where('accounting_period_id', $accountingPeriod->accounting_period_id)->get()->sortDesc();

        return view('daily_restrictions.pages', ['pagesNum' => $pageNums]);
    }

    public function all_restrictions_show($id)
    {
        $user = Auth::user();
        if (! $user->canRead('القيود')) {
            abort(403, 'غير مصرح لك بعرض القيد.');
        }
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
        $dailyEntries = DailyEntrie::with('user', 'creditAccount', 'debitAccount')->where('daily_page_id', $id)
            ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
            ->paginate(20);
        $mainc = MainAccount::all();
        $suba = SubAccount::all();


        return view('daily_restrictions.all_restrictions_show', ['dailyEntries' => $dailyEntries, 'mainc' => $mainc, 'suba' => $suba, "id" => $id]);
    }

    public function edit($id)
    {
        $user = Auth::user();
        if (! $user->canModify('القيود')) {

            abort(403, 'غير مصرح لك.');
        }

        $DailyEntrie = DailyEntrie::where('entrie_id', $id)->first();
        if ($DailyEntrie) {
            if ($DailyEntrie->daily_entries_type == "رصيد افتتاحي") {
                abort(403, 'لا يمكنك تعديل الرصيد الافتتاحي من هنا يمكنك التعديل علية من صفحة الحسابات الفرعية');
                return response()->json(['success' => false, 'errorMessage' => 'لا يمكنك تعديل الرصيد الافتتاحي من هنا يمكنك التعديل علية من صفحة الحسابات الفرعية'], 404);
            }
            if ($DailyEntrie->daily_entries_type == "سند صرف" || $DailyEntrie->daily_entries_type == "سند قبض" || $DailyEntrie->daily_entries_type == "مبيعات" || $DailyEntrie->daily_entries_type == "مردود مبيعات") {
                abort(403, ' لا يمكنك تعديل من هنا');

                return response()->json(['success' => false, 'errorMessage' => 'لا يمكنك تعديل من هنا']);
            }
        }
        $main = MainAccount::all();
        // $main_accounts = MainAccount::all();
        // $curr = Currency::all();
        $PaymentType = PaymentType::cases();
        $transaction_types = TransactionType::cases();
        $Debitsub_account_id = SubAccount::where('sub_account_id', $DailyEntrie->account_debit_id)->first();
        $Creditsub_account_id = SubAccount::where('sub_account_id', $DailyEntrie->account_credit_id)->first();
        $currs = Currency::where('currency_name', $DailyEntrie->currency_name)->first();
        $curr = Currency::all();
        // الحصول على تاريخ اليوم
        $today = Carbon::now()->toDateString(); // الحصول على تاريخ اليوم بصيغة YYYY-MM-DD
        $dailyPage = GeneralJournal::whereDate('created_at', $today)->first(); // البحث عن الصفحة

        if (in_array($DailyEntrie->daily_entries_type, ["تحويل عمله", "شراء عمله", "بيع عمله"])) {
            $dailyEntries = DailyEntrie::where('daily_entries_type', $DailyEntrie->daily_entries_type)
                ->where('invoice_id', $DailyEntrie->invoice_id)
                ->get();
            $DailyEntrieCredit = null;
            $DailyEntrieDebit = null;
            foreach ($dailyEntries as $dailyEntrie) {
                if ($dailyEntrie->amount_debit != 0) {
                    $DailyEntrieDebit = $dailyEntrie;
                }
                if ($dailyEntrie->amount_credit != 0) {
                    $DailyEntrieCredit = $dailyEntrie;
                }
            }


            return view('daily_restrictions.create-currency', [
                'mainAccounts' => $main,
                'DailyEntrieCredit' => $DailyEntrieCredit,
                'DailyEntrie' => $DailyEntrieDebit,
                'sub_account_debit' => $Debitsub_account_id,
                'sub_account_Credit' => $Creditsub_account_id->sub_name,
                'curr' => $curr,
                'PaymentType' => $PaymentType,
                'transaction_types' => $transaction_types,
                'currs' => $currs,
                'dailyPage' => $dailyPage,
                'submitButton' => 'تعديل القيد',

            ]);
        }

        return view('daily_restrictions.create', [
            'main' => $main,
            'DailyEntrie' => $DailyEntrie,
            'sub_account_debit' => $Debitsub_account_id,
            'sub_account_Credit' => $Creditsub_account_id,
            'currs' => $currs,
            'PaymentType' => $PaymentType,
            'transaction_types' => $transaction_types,

            'submitButton' => 'تعديل القيد',
        ]);
    }

    public function  destroy($id)
    {
        $user = Auth::user();
        if (! $user->canDelete('القيود')) {

            abort(403, 'غير مصرح لك.');
        }
        $DailyEntrie = DailyEntrie::where('entrie_id', $id)->first();
        $payment_bond =   PaymentBond::where([
            'transaction_type' => $DailyEntrie->daily_entries_type,
        ])->first();
        if ($DailyEntrie) {


            $generalEntrieaccount_debit_id = GeneralEntrie::where([
                'daily_entry_id' => $DailyEntrie->entrie_id,
                'accounting_period_id' => $DailyEntrie->accounting_period_id,
                'sub_id' => $DailyEntrie->account_debit_id,
            ])->first();
            $generalEntrieaccount_credit_id = GeneralEntrie::where([
                'daily_entry_id' => $DailyEntrie->entrie_id,
                'accounting_period_id' => $DailyEntrie->accounting_period_id,
                'sub_id' => $DailyEntrie->account_credit_id,
            ])->first();
            if ($payment_bond) {
                $payment_bond->delete();
            }
            if ($generalEntrieaccount_debit_id) {
                $generalEntrieaccount_debit_id->delete();
            }
            if ($generalEntrieaccount_credit_id) {
                $generalEntrieaccount_credit_id->delete();
            }
            $message = "إيداع في حساب : " . $DailyEntrie->debitAccount->sub_name . " مبلغ وقدره : " . $DailyEntrie->amount_debit . " تقيد المبلغ في حساب : " . $DailyEntrie->creditAccount->sub_name;
            Operation::createOpertion($DailyEntrie->entrie_id, 'حذف', $DailyEntrie->getTranslatedType(), $message);

            $DailyEntrie->delete();
        } else {

            return response()->json(['success' => true, 'message' => 'لم يتم   حذف  !']);
        }


        return response()->json(['success' => true, 'message' => ' تم   حذف القيد بنجاح!']);
    }
    public function show($id)
    {
        $user = Auth::user();
        if (! $user->canRead('القيود')) {
            abort(403, 'غير مصرح لك بعرض القيد.');
        }
        $mainc = MainAccount::all();
        $suba = SubAccount::all();
        $dailyEntrie = DailyEntrie::where('entrie_id', $id)->first();
        return view('daily_restrictions.show', ['daily' => $dailyEntrie, 'mainc' => $mainc, 'suba' => $suba]);
    }

    public function print($id)
    {
        $user = Auth::user();
        if (! $user->canRead('القيود')) {
            abort(403, 'غير مصرح لك بعرض القيد.');
        }
        $mainc = MainAccount::all();
        $suba = SubAccount::all();
        $dailyEntrie = DailyEntrie::where('entrie_id', $id)->first();
        return view('daily_restrictions.print', ['daily' => $dailyEntrie, 'mainc' => $mainc, 'suba' => $suba]);
    }
}
