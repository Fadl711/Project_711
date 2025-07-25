<?php

namespace App\Http\Controllers\bondController\receipController;

use App\Enum\PaymentType;
use App\Enum\TransactionType;
use App\Http\Controllers\Controller;
use App\Models\AccountingPeriod;
use App\Models\Currency;
use App\Models\DailyEntrie;
use App\Models\GeneralEntrie;
use App\Models\GeneralJournal;
use App\Models\MainAccount;
use App\Models\Operation;
use App\Models\PaymentBond;
use App\Models\SubAccount;
use App\Models\UserPermission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use NumberToWords\NumberToWords;
use SebastianBergmann\Environment\Console;

use function PHPSTORM_META\map;

class ReceipController extends Controller
{
    public function create()
    {
        $main_accounts = MainAccount::all();
        $curr = Currency::all();
                            $PaymentType = PaymentType::cases();
                             $transaction_types = TransactionType::cases();
        // الحصول على تاريخ اليوم
        $today = Carbon::now()->toDateString(); // الحصول على تاريخ اليوم بصيغة YYYY-MM-DD
        $dailyPage = GeneralJournal::whereDate('created_at', $today)->first(); // البحث عن الصفحة
        return view('bonds.receipt_bonds.create', compact('curr', 'dailyPage'), [
            'main_accounts' => $main_accounts,
            'PaymentType' => $PaymentType,
            'transaction_types' => $transaction_types,
             $dailyPage
        ]);
    }

    private function removeCommas($value)
    {
        return floatval(str_replace(',', '', $value)); // إزالة الفواصل وتحويل إلى float

        return floatval(str_replace(',', '', $value)); // إزالة الفواصل وتحويل إلى float
    }
    public function storeUp()
    {

        // استرجاع سندات الصرف المحذوفه




    }
    public function store(Request $request)
    {
        $user = auth()->id();
        $AuthorityName = "السندات";
        $us = UserPermission::where('User_id', $user)
            ->where('Authority_Name', $AuthorityName)
            ->first();
        if (optional($us)->Writing_ability == 1) {

            // تحويل إلى عدد عشري
            $Amount_debit = $this->removeCommas($request->Amount_debit);
            $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();

            if (!$accountingPeriod) {
                return response()->json(['error' => 'لا توجد فترة محاسبية مفتوحة']);
            }
            if (!$request->exchange_rate) {
                return response()->json(['error' => 'يجب ادخال سعر صرف العملة']);
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
                ],
                [
                    'Main_debit_account_id' => $request->AccountReceivable,
                    'Debit_sub_account_id' => $request->DepositAccount,
                    'Main_Credit_account_id' => $request->PaymentParty,
                    'Credit_sub_account_id' => $request->CreditAmount,
                    'payment_type' => $request->payment_type,
                    'Currency_id' => $request->Currency,
                    'exchange_rate' => $request->exchange_rate,
                    'Amount_debit' => $Amount_debit,
                    'transaction_type' => $request->transaction_type,
                    'Statement' => $request->Statement ?? $request->transaction_type,
                    'User_id' => $request->User_id,
                ]
            );
            if ($payment_bond_id) {
                $message = "إيداع في حساب : " . $paymentBond->debitSubAccount->sub_name . " مبلغ وقدره : " . $paymentBond->Amount_debit . " تقيد المبلغ في حساب : " . $paymentBond->creditSubAccount->sub_name;
                Operation::createOpertion($payment_bond_id, 'تعديل', $request->transaction_type, $message);
            }
            DB::table('payment_bonds')
                ->where('payment_bond_id', $paymentBond->payment_bond_id)
                ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
                ->update(['created_at' => Carbon::createFromFormat('Y-m-d', $request->date)]);
            // الحصول على تاريخ اليوم بصيغة YYYY-MM-DD
            $curre = Currency::where('currency_id', $paymentBond->Currency_id)->first();
            // إذا لم توجد صفحة، قم بإنشائها
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
            $Getentrie_id = DailyEntrie::where('invoice_id', $paymentBond->payment_bond_id)
                ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
                ->whereIn('daily_entries_type', ['سند صرف', 'سند قبض'])
                ->first();

            $entrie_id = $Getentrie_id->entrie_id ?? null;
            $daily_page_id = $Getentrie_id->daily_page_id ?? $dailyPage->page_id;
            $DailyEntrie =  DailyEntrie::updateOrCreate(
                [
                    'entrie_id' => $entrie_id,
                    'accounting_period_id' => $accountingPeriod->accounting_period_id,
                ],
                [
                    'daily_entries_type' => $paymentBond->transaction_type,
                    'invoice_id' => $paymentBond->payment_bond_id,
                    'account_debit_id' => $paymentBond->Debit_sub_account_id,
                    'amount_credit' => $Amount_debit ?: 0,
                    'amount_debit' => $Amount_debit ?: 0,
                    'account_credit_id' => $paymentBond->Credit_sub_account_id,
                    'statement' => $paymentBond->Statement,
                    'daily_page_id' => $daily_page_id,
                    'invoice_type' => $paymentBond->payment_type,
                    'currency_name' => $curre->currency_name,
                    'exchange_rate' => $paymentBond->exchange_rate,
                    'user_id' => auth()->user()->id,
                    'status_debit' => 'غير مرحل',
                    'status' => 'غير مرحل',
                ]
            );

            DB::table('daily_entries')
                ->where('entrie_id', $DailyEntrie->entrie_id)
                ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
                ->update(['created_at' => Carbon::createFromFormat('Y-m-d', $request->date)]);
            // $ss=$DailyEntrie->Amount_debit*$DailyEntrie->exchange_rate;
            // dd($ss);


            return response()->json([
                'success' => 'تم بنجاح',
                'payment_bond_id' => $paymentBond->payment_bond_id ?? $payment_bond_id
            ]);
        }
    }
    public function show($id)
    {

        $PaymentBond = PaymentBond::where('payment_bond_id', $id)->first();
        return view('bonds.receipt_bonds.show', compact('PaymentBond'));
    }
    public function edit($id)
    {
        $user = auth()->id();
        $AuthorityName = "السندات";
        $us = UserPermission::where('User_id', $user)
            ->where('Authority_Name', $AuthorityName)
            ->first();
        if (optional($us)->Ability_modify == 1) {
            $ExchangeBond = PaymentBond::where('payment_bond_id', $id)->first();
            $main_accounts = MainAccount::all();
            $SubAccounts = SubAccount::all();
            $Debitsub_account_id = SubAccount::where('sub_account_id', $ExchangeBond->Debit_sub_account_id)->first();
            $Creditsub_account_id = SubAccount::where('sub_account_id', $ExchangeBond->Credit_sub_account_id)->first();
            $submitButton = "تعديل السند";


            $currs = Currency::where('currency_id', $ExchangeBond->Currency_id)->first();
            // الحصول على تاريخ اليوم
            $today = Carbon::now()->toDateString(); // الحصول على تاريخ اليوم بصيغة YYYY-MM-DD
            $dailyPage = GeneralJournal::whereDate('created_at', $today)->first(); // البحث عن الصفحة
                    $PaymentType = PaymentType::cases();


            return view('bonds.receipt_bonds.create', [
                'ExchangeBond' => $ExchangeBond,
                'Debitsub_account_id' => $Debitsub_account_id,
                'Creditsub_account_id' => $Creditsub_account_id,
                'currs' => $currs,
                'main_accounts' => $main_accounts,
                'SubAccounts' => $SubAccounts,
                'dailyPage' => $dailyPage,
                'PaymentType' => $PaymentType,
                'submitButton' => 'تعديل السند',
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'لا توجد  لديك صلاحيه.',
            ], 400);
        }
    }

    public function destroy($id)
    {
        $user = auth()->id();
        $AuthorityName = "السندات";
        $us = UserPermission::where('User_id', $user)
            ->where('Authority_Name', $AuthorityName)
            ->first();

        if (optional($us)->Deletion_authority == 1) {
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
                // التحقق من وجود قيود يومية مرتبطة
                $DailyEntrie = DailyEntrie::where('invoice_id', $paymentBond->payment_bond_id)
                    ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
                    ->where('daily_entries_type', $paymentBond->transaction_type)
                    ->first();

                if ($DailyEntrie) {
                    // حذف General Entries المرتبطة
                    GeneralEntrie::where([
                        'daily_entry_id' => $DailyEntrie->entrie_id,
                        'accounting_period_id' => $DailyEntrie->accounting_period_id,
                        'sub_id' => $DailyEntrie->account_debit_id,
                    ])->delete();

                    GeneralEntrie::where([
                        'daily_entry_id' => $DailyEntrie->entrie_id,
                        'accounting_period_id' => $DailyEntrie->accounting_period_id,
                        'sub_id' => $DailyEntrie->account_credit_id,
                    ])->delete();
                    // حذف Daily Entry
                    $DailyEntrie->delete();
                }
                $message = "إيداع في حساب : " . $paymentBond->debitAccount->sub_name . " مبلغ وقدره : " . $paymentBond->amount_debit . " تقيد المبلغ في حساب : " . $paymentBond->creditAccount->sub_name;
                Operation::createOpertion($paymentBond->payment_bond_id, 'حذف', $paymentBond->transaction_type, $message);
                // حذف سند الدفع
                $paymentBond->delete();

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
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'لا يوجد لديك صلاحية',
            ], 403); // 403 Forbidden أكثر ملاءمة لحالات عدم الصلاحية
        }
    }


    public function print($id)
    {
        $PaymentBond = PaymentBond::where('payment_bond_id', $id)->first();
        $subAccount = SubAccount::where('sub_account_id', $PaymentBond->Debit_sub_account_id)->first();
        $Credit_sub = SubAccount::where('sub_account_id', $PaymentBond->Credit_sub_account_id)->first();
        $currs = Currency::where('currency_id', $PaymentBond->Currency_id)->first();
        $sub_name = $subAccount->sub_name;
        $Credit_sub_name = $Credit_sub->sub_name;
        if ($PaymentBond->payment_type === 1) {
            $payment_type = "نقداً";
        }
        if ($PaymentBond->payment_type === 2) {
            $payment_type = "أجل";
        }
        if ($PaymentBond->payment_type === 3) {
            $payment_type = "تحويل بنكي";
        }
        if ($PaymentBond->payment_type === 4) {
            $payment_type = "شيك";
        }
        $currency_name = $currs->currency_name ?? '';
        // جلب البيانات وتحويلها
        $numberToWords = new NumberToWords();
        $numberTransformer = $numberToWords->getNumberTransformer('ar'); // اللغة العربية
        $result = is_numeric($PaymentBond->Amount_debit)
            ? $numberTransformer->toWords($PaymentBond->Amount_debit) . ' ' . $currency_name
            : 'القيمة غير صالحة';
        // استخدام $currency بشكل صحيح
        return view('bonds.receipt_bonds.print', compact('payment_type', 'PaymentBond', 'result', 'currency_name', 'sub_name', 'Credit_sub_name'));
    }
    public function getPaymentBond(Request $request, $filterType)
    {
        // التحقق من المدخلات
        $validated = $request->validate([
            'searchType' => 'nullable|string|in:كل السندات,أول سند,آخر سند',
            'transactionType' => 'nullable|string',
            'searchQuery' => 'nullable|string|max:255',
            'fromDate' => 'nullable|date',
            'toDate' => 'nullable|date',
        ]);

        // الحصول على آخر فترة محاسبية نشطة
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();

        if (!$accountingPeriod) {
            return response()->json(['message' => 'لم يتم العثور على فترة محاسبية حالية.'], 404);
        }
        // إنشاء استعلام السندات
        $query = PaymentBond::with(['creditSubAccount', 'debitSubAccount', 'user'])
            ->where('transaction_type',  $validated['transactionType']);

        // تطبيق الفلترة بناءً على نوع الفلترة (تواريخ)
        switch ($filterType) {


            case '1': // اليوم
                $query->where('accounting_period_id', $accountingPeriod->accounting_period_id);
                break;
            case '2': // اليوم
                $query->whereDate('created_at', now()->toDateString());
                $query->where('accounting_period_id', $accountingPeriod->accounting_period_id);
                break;
            case '3': // هذا الأسبوع
                $query->where('accounting_period_id', $accountingPeriod->accounting_period_id);

                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case '4': // هذا الشهر
                $query->where('accounting_period_id', $accountingPeriod->accounting_period_id);

                $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                break;
            case '5':

                // الفترة المخصصة
                if ($request->filled(['fromDate', 'toDate'])) {

                    $query->whereBetween('created_at', [$validated['fromDate'], $validated['toDate']]);
                }
                break;

                //  if ($validated(['fromDate', 'toDate'])) {
                // $query->whereBetween('created_at', [$validated['fromDate'], $validated['toDate']]);
                // }
                // break;
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
            'fromDate' => 'nullable',
            'toDate' => 'nullable',
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
            ->where('transaction_type', $validated['transactionType']);

        // التحقق من وجود searchQuery وتطبيقه


        // ترتيب الفواتير حسب نوع البحث

        if (isset($validated['fromDate']) && isset($validated['toDate'])) {
            $query->whereBetween('created_at', [$validated['fromDate'], $validated['toDate']]);

            // dd($validated['fromDate'],$validated['toDate']);
        } else {
            $query->where('accounting_period_id', $accountingPeriod->accounting_period_id);
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
}
