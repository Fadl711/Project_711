<?php

namespace App\Http\Controllers;

use App\Enum\PaymentType;
use App\Enum\TransactionType;
use App\Models\AccountingPeriod;
use App\Models\Currency;
use App\Models\DailyEntrie;
use App\Models\Double_entry;
use App\Models\GeneralJournal;
use App\Models\MainAccount;
use App\Models\Product;
use App\Models\SubAccount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;

class DoubleEntryController extends Controller
{
    private function removeCommas($value)
    {
        return floatval(str_replace(',', '', $value)); // إزالة الفواصل وتحويل إلى float
    }
    public function create()
    {
        $Currency_name = Currency::all();
        $products = Product::all();
        $PaymentType = PaymentType::cases();
        $transaction_types = TransactionType::cases();



        $allSubAccounts = SubAccount::all();
        $main_accounts = MainAccount::all();
        return view(
            'daily_restrictions.double_entries.create',
            [
                'AllSubAccounts' => $allSubAccounts,
                'Currency_name' => $Currency_name,
                'main_accounts' => $main_accounts,
                'products' => $products,
                'PaymentType' => $PaymentType,
                'transaction_types' => $transaction_types,
            ]
        );
    }
    public function show($id)
    {
        $doubleEntry = Double_entry::with('double_entries', 'creditAccount', 'debitAccount')->find($id);
        return view('daily_restrictions.double_entries.show_dobule', compact('doubleEntry'));
    }
    public function allDoubleEntries()
    {
        $doubleEntries = Double_entry::with('double_entries', 'creditAccount', 'debitAccount')->get()->sortDesc();
        return view('daily_restrictions.double_entries.all_double_entries', compact('doubleEntries'));
    }
    public function edit($id)
    {
        $DailyEntrie = DailyEntrie::where('entrie_id', $id)->first();
        return response()->json($DailyEntrie);
    }
    public function storeOrUpdate(Request $request)
    {


        $request->validate(
            [
                'typeAccount' => 'required',
            ]
        );
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();

        $Double_entry = Double_entry::updateOrCreate(
            ['id' => $request->saveData_debit_id2],
            [
                'account_id' => $request->sub_account_debit_id,
                'Statement' => $request->Statement,
                'User_id' => Auth::user()->id,
                'account_type' => $request->typeAccount,
            ]
        );
        return response()->json($Double_entry);
    }


    public function destroy($id)
    {
        $double_entry = Double_entry::findOrFail($id);
        $double_entry->double_entries()->delete();
        $double_entry->delete();

        return response()->json([
            'success' =>
            'تم حذف القيد المزدوج وقيوده بنجاح'
        ]);
    }


    public function store(Request $request)

    {

        $dailyPageId = DailyEntrie::where('entrie_id', (int)$request->entrie_id)->first();
        /*  if ($dailyPageId) {
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
        } */
        $Amount_debit = $this->removeCommas(number_format($request->Amount_debit));
        //    dd((int)$request->sub_account_debit_id );
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
        // $validated = $request->validate([
        //     'sub_account_debit_id' => 'required|',
        //     'Amount_debit' => 'required',
        //     'sub_account_Credit_id' => 'required|',
        //     'Statement' => 'nullable|',
        //     'Currency_name' =>  'nullable|', // تأكد من استخدام الاسم الصحيح هنا
        //     'exchange_rate' => 'required',
        // ]);
        // التأكد من عدم اختيار حسابين فرعيين متماثلين
        if ((int)$request->sub_account_debit_id == (int)$request->sub_account_Credit_id) {
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
        if ((int)$request->Invoice_type) {
            $transactionType = TransactionType::fromValue((int)$request->Invoice_type);
            if ($transactionType) {
                $invoice_type = $transactionType->label(); // جلب التسمية النصية
            } else {

                throw new InvalidArgumentException('نوع الفاتورة غير معروف.');
            }
        }
        // تحديد النوع الافتراضي
        $defaultPaymentType = 'قيد مزدوج';
        $Invoice_id = null;
        $Payment_type = $defaultPaymentType;



        // // إنشاء القيد اليومي
        $dailyEntrie = DailyEntrie::updateOrCreate(
            [
                'entrie_id' => (int)$request->entrie_id,
                'accounting_period_id' => $accountingPeriod->accounting_period_id,

            ],
            [
                'double_entry_id'
                => (int)$request->saveData_debit_id,
                'invoice_id' => $Invoice_id ?? null,
                'daily_page_id' => $dailyPage->page_id ?? $dailyPageId->daily_page_id,
                'daily_entries_type' => $invoice_type ?? $Payment_type,
                'account_debit_id' => $request->sub_account_type == "دائن" ? $request->sub_account_debit_id : $request->sub_account_Credit_id,
                'amount_credit' => $Amount_debit,
                'amount_debit' =>  $Amount_debit,
                'account_credit_id' => $request->sub_account_type == "مدين" ? $request->sub_account_debit_id : $request->sub_account_Credit_id,
                'exchange_rate' => number_format($request->exchange_rate),
                'statement' => $request->Statement  ?? "قيد يومي",
                'invoice_type' => (int)$request->payment_type,
                'currency_name' => $request->Currency_name,
                'user_id' => Auth::user()->id,

                'status_debit' => 'غير مرحل',
                'status' => 'غير مرحل',
            ]
        );
        $dailyEntrie->load('creditAccount', 'debitAccount');

        return response()->json(['success' => 'تم حفظ القيد بنجاح', 'dailyEntrie' => $dailyEntrie]);
    }
}
