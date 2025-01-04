<?php

namespace App\Http\Controllers\DailyRestrictionController;

use App\Enum\TransactionType;
use App\Http\Controllers\Controller;
use App\Models\AccountingPeriod;
use App\Models\Currency;
use App\Models\DailyEntrie;
use App\Models\ExchangeBond;
use App\Models\GeneralEntrie;
use App\Models\GeneralJournal;
use App\Models\MainAccount;
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
        $dailyPageId=DailyEntrie::where('entrie_id',$request->entrie_id)->first();
        if ($dailyPageId) {
            if($dailyPageId->daily_entries_type=="رصيد افتتاحي")
            {
                return response()->json(['success'=>false,'errorMessage' => 'لا يمكنك تعديل الرصيد الافتتاحي من هنا يمكنك التعديل علية من صفحة الحسابات الفرعية']);
            }
        }
        $Amount_debit = $this->removeCommas($request->Amount_debit);
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
        $validated = $request->validate([
            'sub_account_debit_id' => 'required|integer',
            'Amount_debit' => 'required',
            // 'entrie_id' => 'nullable|integer',
            'sub_account_Credit_id' => 'required|integer',
            'Statement' => 'nullable|string',
            'Currency_name' =>  'nullable|string', // تأكد من استخدام الاسم الصحيح هنا
            'User_id' => 'required|integer',
        ]);
        // التأكد من عدم اختيار حسابين فرعيين متماثلين
        if ($request->sub_account_debit_id == $request->sub_account_Credit_id) {
            return response()->json(['success' => 'يجب عدم تساوي الحسابات الفرعية المدين والدائن.']);
        }
        if(!$dailyPageId)
        {
            // الحصول على تاريخ اليوم بصيغة YYYY-MM-DD
        $today = Carbon::now()->toDateString();
        $dailyPage = GeneralJournal::whereDate('created_at', $today)->latest()->first();


        // إذا كنت بحاجة لإنشاء سجل جديد في حال عدم وجود سجلات على الإطلاق
        if (!$dailyPage) {
            $dailyPage = GeneralJournal::create([
                'accounting_period_id'=>$accountingPeriod->accounting_period_id,
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
if($request->Invoice_type){
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
$mainc=MainAccount::all();
$suba=SubAccount::all();
// // إنشاء القيد اليومي
$dailyEntrie = DailyEntrie::updateOrCreate(
    [
        'entrie_id'=>$request->entrie_id ,
        'accounting_period_id' => $accountingPeriod->accounting_period_id,

    ],
    [
        'Daily_page_id' => $dailyPage->page_id ??$dailyPageId->Daily_page_id,
        'daily_entries_type' =>$invoice_type ?? $Payment_type,
        'Invoice_id' =>  $Invoice_id??null,
        'account_debit_id' => $validated['sub_account_debit_id'],
        'Amount_Credit' => $Amount_debit,
        'Amount_debit' =>  $Amount_debit ,
        'account_Credit_id' => $validated['sub_account_Credit_id'],
        'Statement' => $validated['Statement']  ?? "قيد يومي",
        'Invoice_type' => $request->payment_type ,
        'Currency_name' => $validated['Currency_name'],
        'User_id' =>$validated['User_id'],
        'status_debit' => 'غير مرحل',
        'status' => 'غير مرحل',
    ]
);
// return view('daily_restrictions.show',['daily'=>$dailyEntrie,'mainc'=>$mainc,'suba'=>$suba]);

        return response()->json(['success' => 'تم حفظ القيد بنجاح','entrie_id'=>$dailyEntrie->entrie_id]);
    }
    public function saveAndPrint(Request $request)
    {


    }
public function stor(Request $request){
    $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
    if (!$accountingPeriod) {
        return response()->json(['success' => false, 'message' => 'لا توجد فترة محاسبية مفتوحة.']);
    }
    $today = Carbon::now()->toDateString(); // الحصول على تاريخ اليوم بصيغة YYYY-MM-DD
    $dailyPage = GeneralJournal::whereDate('created_at', $today)->first(); // البحث عن الصفحة
    if ($dailyPage) {
        $generalJournal1=GeneralJournal::where('accounting_period_id',$accountingPeriod->accounting_period_id)->get();
        $mainAccount=MainAccount::all();
        $curr=Currency::all();
        return view('daily_restrictions.create',compact('curr','dailyPage'),['mainAccounts'=> $mainAccount]);
    } else {
        $Statement=$request->Statement;
        GeneralJournal::create([
            'accounting_period_id'=>$accountingPeriod->accounting_period_id,
        ]);
            // الحصول على تاريخ اليوم بصيغة YYYY-MM-DD
            $today = Carbon::now()->toDateString();
            $dailyPage = GeneralJournal::whereDate('created_at', $today)->first(); // البحث عن الصفحة
            if ($dailyPage) {
                // إذا تم العثور على الصفحة، عرض رقم الصفحة
                $generalJournal1=GeneralJournal::where('accounting_period_id',$accountingPeriod->accounting_period_id)->get();
                $mainAccount=MainAccount::all();
            $curr=Currency::all();
            return view('daily_restrictions.create',compact('curr','dailyPage'),['mainAccounts'=> $mainAccount]);
            }
    }

}

    public function create(){
        $mainAccount=MainAccount::all();
        $curr=Currency::all();
        // الحصول على تاريخ اليوم
        $today = Carbon::now()->toDateString(); // الحصول على تاريخ اليوم بصيغة YYYY-MM-DD
        $dailyPage = GeneralJournal::whereDate('created_at', $today)->first(); // البحث عن الصفحة
        return view('daily_restrictions.create',compact('curr','dailyPage'),['mainAccounts'=> $mainAccount,$dailyPage]);
    }

    public function index()
    {
        return view('daily_restrictions.index');
    }

    public function   all_restrictions_show_1()
    {    $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();

        // $pageNums=GeneralJournal::all();
        $pageNums=GeneralJournal::where('accounting_period_id',$accountingPeriod->accounting_period_id)->get();

        return view('daily_restrictions.all_restrictions_show1',['pagesNum'=>$pageNums,]);
    }

    public function   all_restrictions_show($id)
    {
        $eail=DailyEntrie::where('Daily_page_id',$id)->get();
        $mainc=MainAccount::all();
        $suba=SubAccount::all();


        return view('daily_restrictions.all_restrictions_show',['eail'=>$eail,'mainc'=>$mainc,'suba'=>$suba,"id"=>$id]);
    }

    public function edit($id)
    {
        $DailyEntrie=DailyEntrie::where('entrie_id',$id)->first();
        $main=MainAccount::all();
        $Debitsub_account_id=SubAccount::where('sub_account_id',$DailyEntrie->account_debit_id)->first();
        $Creditsub_account_id=SubAccount::where('sub_account_id',$DailyEntrie->account_Credit_id)->first();
        $currs=Currency::where('currency_name',$DailyEntrie->Currency_name)->first();
        // الحصول على تاريخ اليوم
        $today = Carbon::now()->toDateString(); // الحصول على تاريخ اليوم بصيغة YYYY-MM-DD
        $dailyPage = GeneralJournal::whereDate('created_at',$today)->first(); // البحث عن الصفحة
        if ($DailyEntrie) {
            if($DailyEntrie->daily_entries_type=="رصيد افتتاحي")
            {

                return back()->with('success', 'لايمكن تعديل قيد رصيد افتتاحي');

            }
        }
        return view('daily_restrictions.create', [
            'main'=>$main,
            'DailyEntrie' => $DailyEntrie ,
            'sub_account_debit' => $Debitsub_account_id,
            'sub_account_Credit' => $Creditsub_account_id,
            'currs' => $currs,
            'submitButton' => 'تعديل القيد',
        ]);
         }

//     public function update(Request $request,$id){
//         $today = Carbon::now()->toDateString();
//         $dailyPage = GeneralJournal::whereDate('created_at', $today)->first();
//         $Currency=Currency::where('currency_name',$request->Currency_name)->value('currency_id');


//         $DailyEntrie =DailyEntrie::where('entrie_id',$id)->first();
// $ct=$DailyEntrie->Daily_page_id;
//         ExchangeBond::where('created_at',$DailyEntrie->created_at)->update([
//             'Debit_sub_account_id'=>$request['sub_account_debit_id'],
//             'Amount_debit'=>$request['Amount_debit'],
//             'Credit_sub_account_id'=>$request['sub_account_Credit_id'],
//             'Statement'=> $request['Statement'],
//             'Currency_id'=>$Currency,

//             'User_id'=>$request['User_id'],
//         ]);
//         PaymentBond::where('created_at',$DailyEntrie->created_at)->update([
//             'Debit_sub_account_id'=>$request['sub_account_debit_id'],
//             'Amount_debit'=>$request['Amount_debit'],
//             'Credit_sub_account_id'=>$request['sub_account_Credit_id'],
//             'Statement'=> $request['Statement'],
//             'Currency_id'=>$Currency,
//             'User_id'=>$request['User_id'],
//         ]);
//         $DailyEntrie->update([
//             'account_debit_id'=>$request['sub_account_debit_id'],
//             'Amount_debit'=>$request['Amount_debit'],
//             'account_Credit_id'=>$request['sub_account_Credit_id'],
//             'Amount_Credit'=>$request['Amount_debit'],
//             'Statement'=> $request['Statement'],
//             'Currency_name'=>$request['Currency_name'],
//             'Daily_page_id'=>$dailyPage->page_id,
//             'User_id'=>$request['User_id'],
//         ]);

//         return redirect()->route('all_restrictions_show',$ct);
//     }
    public function  destroy($id){
        $DailyEntrie=DailyEntrie::where('entrie_id',$id)->first();
        ExchangeBond::where('created_at',$DailyEntrie->created_at)->delete();
        PaymentBond::where('created_at',$DailyEntrie->created_at)->delete();
        $generalEntrieaccount_debit_id = GeneralEntrie::where([
            'Daily_entry_id' => $DailyEntrie->entrie_id,
            'accounting_period_id' => $DailyEntrie->accounting_period_id,
            'sub_id' => $DailyEntrie->account_debit_id,
        ])->delete();
        $generalEntrieaccount_debit_id = GeneralEntrie::where([
            'Daily_entry_id' => $DailyEntrie->entrie_id,
            'accounting_period_id' => $DailyEntrie->accounting_period_id,
            'sub_id' => $DailyEntrie->account_Credit_id,
        ])->delete();
        $DailyEntrie->delete();

        return response()->json(['success' =>true,'message'=> 'تم   حذف القيد بنجاح!']);

        // return back();
    }
    public function show($id)
    {
        $mainc=MainAccount::all();
        $suba=SubAccount::all();
        $dailyEntrie =DailyEntrie::where('entrie_id',$id)->first();
        return view('daily_restrictions.show',['daily'=>$dailyEntrie,'mainc'=>$mainc,'suba'=>$suba]);
    }

    public function print($id){
        $mainc=MainAccount::all();
        $suba=SubAccount::all();
        $dailyEntrie =DailyEntrie::where('entrie_id',$id)->first();
        return view('daily_restrictions.print',['daily'=>$dailyEntrie,'mainc'=>$mainc,'suba'=>$suba]);
    }
    public function search(Request $request)
    {
        if ($request->ajax()) {
            $mainc = MainAccount::all();
            $suba = SubAccount::all();
            $output = '';
            $query = $request->get('search');
            $Daily_page_id = $request->get('Value');  // هنا يمكنك تعريف المتغيرات التي تحتاجها في كلا الحالتين
            $products = null; // متغير سيحتوي على النتائج
            if ($query != '') {
                $products = DailyEntrie::where('daily_page_id', $Daily_page_id) // شرط رقم الصفحة
                ->where(function($q) use ($query) {
                    $q->where('entrie_id', 'LIKE', '%'.$query.'%')
                      ->orWhereHas('debitAccount', function ($q) use ($query) {
                          $q->where('sub_name', 'LIKE', "%$query%");
                      });
                })
                ->get();
                if ($products)
                 {
                    foreach ($products as $product) {
                        $resultDebit=null;
                        $resultDebit1=null;
                        $resultDebit1=$suba->where('sub_account_id',$product->account_debit_id);
                        foreach ($resultDebit1 as $key) {
                            $resultDebit=$mainc->where('main_account_id',$key->Main_id)->first();
                            if ($key->sub_name!=$resultDebit->account_name)
                            {
                                $resultDebit1=$key;
                                break;
                            }
                        }
                        $resultCredit=null;
                        $resultCredit1=null;
                        $resultCredit1=$suba->where('sub_account_id',$product->account_Credit_id);
                        foreach ($resultCredit1 as $key) {
                            $resultCredit=$mainc->where('main_account_id',$key->Main_id)->first();
                            if ($key->sub_name!=$resultCredit->account_name)
                            {
                                $resultCredit1=$key;
                                break;
                            }
                        }
                        $output .= '<tr class=" transition-all duration-500">'.
                        '<td class="border text-right">'.$product->entrie_id.'</td>'.
                            '<td class="border text-right">'.$product->daily_entries_type.'</td>'.
                            '<td class="border text-right">'.'('.$resultDebit->account_name.')'.'-'.'('.$resultDebit1->sub_name.')'.'</td>'.
                        '<td class="border text-right">'.$product->Amount_debit.'</td>'.
                        '<td class="border text-right">'.'('.$resultCredit->account_name.')'.'-'.'('.$resultCredit1->sub_name.')'.'</td>'.
                        '<td class="border text-right">'.$product->Amount_Credit.'</td>'.
                        '<td class="border text-right">'.$product->Statement.'</td>'.
                        '<td class="border text-right">'.$product->created_at.'</td>'.
                        '<td class="border text-right">'.$product->updated_at.'</td>'.
                        '<td class="border text-right">'.
                        ($product->status == 'مرحل' ? '<span class="text-success">مرحل</span>' : '<span class="text-danger">غير مرحل</span>').
                    '</td>'.
                        '<td class="border text-right">'.$product->user->name.'</td>'.
                        '<td class="p-1 border text-right flex">'.
                            '<a href="'.route('restrictions.show', $product->entrie_id).'" class="p-1 rounded-full group transition-all duration-500 flex item-center">'
                            .'<svg class="w-6 h-6 text-[#2430d3] dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">'.
                            '<path stroke="currentColor" stroke-width="2" d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z"/>'.
                            '<path stroke="currentColor" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>'.
                          '</svg>'.
                                '</a>'.
                            '<a href="'.route('restrictions.edit', $product->entrie_id).'" class="p-1 rounded-full group transition-all duration-500 flex item-center">'
                            .'<svg class="w-6 h-6 text-[#2430d3] dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">'
                            .'<svg class="w-6 h-6 text-[#2430d3] dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">'
                            .'<path fill-rule="evenodd" d="M11.32 6.176H5c-1.105 0-2 .949-2 2.118v10.588C3 20.052 3.895 21 5 21h11c1.105 0 2-.948 2-2.118v-7.75l-3.914 4.144A2.46 2.46 0 0 1 12.81 16l-2.681.568c-1.75.37-3.292-1.263-2.942-3.115l.536-2.839c.097-.512.335-.983.684-1.352l2.914-3.086Z" clip-rule="evenodd"/>'
                            .'<path fill-rule="evenodd" d="M19.846 4.318a2.148 2.148 0 0 0-.437-.692 2.014 2.014 0 0 0-.654-.463 1.92 1.92 0 0 0-1.544 0 2.014 2.014 0 0 0-.654.463l-.546.578 2.852 3.02.546-.579a2.14 2.14 0 0 0 .437-.692 2.244 2.244 0 0 0 0-1.635ZM17.45 8.721 14.597 5.7 9.82 10.76a.54.54 0 0 0-.137.27l-.536 2.84c-.07.37.239.696.588.622l2.682-.567a.492.492 0 0 0 .255-.145l4.778-5.06Z" clip-rule="evenodd"/>'
                          .'</svg>'.
                                '</a>'.
                            '<a href="#" class="mt-[10px]  rounded-full  group transition-all duration-500  flex item-center delete-payment" data-id="'.$product->entrie_id.'" >'.
                            '<svg class="" width="18" height="18" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">'.
                            '<path class="fill-red-600" d="M4.00031 5.49999V4.69999H3.20031V5.49999H4.00031ZM16.0003 5.49999H16.8003V4.69999H16.0003V5.49999ZM17.5003 5.49999L17.5003 6.29999C17.9421 6.29999 18.3003 5.94183 18.3003 5.5C18.3003 5.05817 17.9421 4.7 17.5003 4.69999L17.5003 5.49999ZM9.30029 9.24997C9.30029 8.80814 8.94212 8.44997 8.50029 8.44997C8.05847 8.44997 7.70029 8.80814 7.70029 9.24997H9.30029ZM7.70029 13.75C7.70029 14.1918 8.05847 14.55 8.50029 14.55C8.94212 14.55 9.30029 14.1918 9.30029 13.75H7.70029ZM12.3004 9.24997C12.3004 8.80814 11.9422 8.44997 11.5004 8.44997C11.0585 8.44997 10.7004 8.80814 10.7004 9.24997H12.3004ZM10.7004 13.75C10.7004 14.1918 11.0585 14.55 11.5004 14.55C11.9422 14.55 12.3004 14.1918 12.3004 13.75H10.7004ZM4.00031 6.29999H16.0003V4.69999H4.00031V6.29999ZM15.2003 5.49999V12.5H16.8003V5.49999H15.2003ZM11.0003 16.7H9.00031V18.3H11.0003V16.7ZM4.80031 12.5V5.49999H3.20031V12.5H4.80031ZM9.00031 16.7C7.79918 16.7 6.97882 16.6983 6.36373 16.6156C5.77165 16.536 5.49093 16.3948 5.29823 16.2021L4.16686 17.3334C4.70639 17.873 5.38104 18.0979 6.15053 18.2013C6.89702 18.3017 7.84442 18.3 9.00031 18.3V16.7ZM3.20031 12.5C3.20031 13.6559 3.19861 14.6033 3.29897 15.3498C3.40243 16.1193 3.62733 16.7939 4.16686 17.3334L5.29823 16.2021C5.10553 16.0094 4.96431 15.7286 4.88471 15.1366C4.80201 14.5215 4.80031 13.7011 4.80031 12.5H3.20031ZM15.2003 12.5C15.2003 13.7011 15.1986 14.5215 15.1159 15.1366C15.0363 15.7286 14.8951 16.0094 14.7024 16.2021L15.8338 17.3334C16.3733 16.7939 16.5982 16.1193 16.7016 15.3498C16.802 14.6033 16.8003 13.6559 16.8003 12.5H15.2003ZM11.0003 18.3C12.1562 18.3 13.1036 18.3017 13.8501 18.2013C14.6196 18.0979 15.2942 17.873 15.8338 17.3334L14.7024 16.2021C14.5097 16.3948 14.229 16.536 13.6369 16.6156C13.0218 16.6983 12.2014 16.7 11.0003 16.7V18.3ZM2.50031 4.69999C2.22572 4.7 2.04405 4.7 1.94475 4.7C1.89511 4.7 1.86604 4.7 1.85624 4.7C1.85471 4.7 1.85206 4.7 1.851 4.7C1.05253 5.50059 1.85233 6.3 1.85256 6.3C1.85273 6.3 1.85297 6.3 1.85327 6.3C1.85385 6.3 1.85472 6.3 1.85587 6.3C1.86047 6.3 1.86972 6.3 1.88345 6.3C1.99328 6.3 2.39045 6.3 2.9906 6.3C4.19091 6.3 6.2032 6.3 8.35279 6.3C10.5024 6.3 12.7893 6.3 14.5387 6.3C15.4135 6.3 16.1539 6.3 16.6756 6.3C16.9364 6.3 17.1426 6.29999 17.2836 6.29999C17.3541 6.29999 17.4083 6.29999 17.4448 6.29999C17.4631 6.29999 17.477 6.29999 17.4863 6.29999C17.4909 6.29999 17.4944 6.29999 17.4968 6.29999C17.498 6.29999 17.4988 6.29999 17.4994 6.29999C17.4997 6.29999 17.4999 6.29999 17.5001 6.29999C17.5002 6.29999 17.5003 6.29999 17.5003 5.49999C17.5003 4.69999 17.5002 4.69999 17.5001 4.69999C17.4999 4.69999 17.4997 4.69999 17.4994 4.69999C17.4988 4.69999 17.498 4.69999 17.4968 4.69999C17.4944 4.69999 17.4909 4.69999 17.4863 4.69999C17.477 4.69999 17.4631 4.69999 17.4448 4.69999C17.4083 4.69999 17.3541 4.69999 17.2836 4.69999C17.1426 4.7 16.9364 4.7 16.6756 4.7C16.1539 4.7 15.4135 4.7 14.5387 4.7C12.7893 4.7 10.5024 4.7 8.35279 4.7C6.2032 4.7 4.19091 4.7 2.9906 4.7C2.39044 4.7 1.99329 4.7 1.88347 4.7C1.86974 4.7 1.86051 4.7 1.85594 4.7C1.8548 4.7 1.85396 4.7 1.85342 4.7C1.85315 4.7 1.85298 4.7 1.85288 4.7C1.85284 4.7 2.65253 5.49941 1.85408 6.3C1.85314 6.3 1.85296 6.3 1.85632 6.3C1.86608 6.3 1.89511 6.3 1.94477 6.3C2.04406 6.3 2.22573 6.3 2.50031 6.29999L2.50031 4.69999ZM7.05028 5.49994V4.16661H5.45028V5.49994H7.05028ZM7.91695 3.29994H12.0836V1.69994H7.91695V3.29994ZM12.9503 4.16661V5.49994H14.5503V4.16661H12.9503ZM12.0836 3.29994C12.5623 3.29994 12.9503 3.68796 12.9503 4.16661H14.5503C14.5503 2.8043 13.4459 1.69994 12.0836 1.69994V3.29994ZM7.05028 4.16661C7.05028 3.68796 7.4383 3.29994 7.91695 3.29994V1.69994C6.55465 1.69994 5.45028 2.8043 5.45028 4.16661H7.05028ZM2.50031 6.29999C4.70481 6.29998 6.40335 6.29998 8.1253 6.29997C9.84725 6.29996 11.5458 6.29995 13.7503 6.29994L13.7503 4.69994C11.5458 4.69995 9.84724 4.69996 8.12529 4.69997C6.40335 4.69998 4.7048 4.69998 2.50031 4.69999L2.50031 6.29999ZM13.7503 6.29994L17.5003 6.29999L17.5003 4.69999L13.7503 4.69994L13.7503 6.29994ZM7.70029 9.24997V13.75H9.30029V9.24997H7.70029ZM10.7004 9.24997V13.75H12.3004V9.24997H10.7004Z" fill="#F87171"></path>'.
                            ' </svg>'.
                            '</a>'.
                        '</td>'.

                                '</tr>'
                                ;


                    }
                    return Response($output);
                }
            }else {

                $products= DailyEntrie::where('Daily_page_id',$Daily_page_id)->get();
                // إذا كان الحقل فارغًا، أرجع جميع المنتجات
                foreach ($products as $product) {

                    $resultDebit=null;
                    $resultDebit1=null;
                    $resultDebit1=$suba->where('sub_account_id',$product->account_debit_id);
                    foreach ($resultDebit1 as $key) {
                        $resultDebit=$mainc->where('main_account_id',$key->Main_id)->first();
                        if ($key->sub_name!=$resultDebit->account_name) {
                            $resultDebit1=$key;
                            break;

                        }
                    }
                    $resultCredit=null;
                    $resultCredit1=null;
                    $resultCredit1=$suba->where('sub_account_id',$product->account_Credit_id);
                    foreach ($resultCredit1 as $key) {
                        $resultCredit=$mainc->where('main_account_id',$key->Main_id)->first();
                        if ($key->sub_name!=$resultCredit->account_name) {
                            $resultCredit1=$key;
                            break;
                        }
                        # code...
                    }

                    $output .= '<tr class=" transition-all duration-500">'.
                    '<td class="border text-right">'.$product->entrie_id.'</td>'.
                    '<td class="border text-right">'.$product->daily_entries_type.'</td>'.
                    '<td class="border text-right">'.'('.$resultDebit->account_name.')'.'-'.'('.$resultDebit1->sub_name.')'.'</td>'.
                    '<td class="border text-right">'.$product->Amount_debit.'</td>'.
                    '<td class="border text-right">'.'('.$resultCredit->account_name.')'.'-'.'('.$resultCredit1->sub_name.')'.'</td>'.
                    '<td class="border text-right">'.$product->Amount_Credit.'</td>'.
                    '<td class="border text-right">'.$product->Statement.'</td>'.
                    '<td class="border text-right">'.$product->created_at.'</td>'.
                    '<td class="border text-right">'.$product->updated_at.'</td>'.
                    '<td class="border text-right">'.
                    ($product->status == 'مرحل' ? '<span class="text-success">مرحل</span>' : '<span class="text-danger">غير مرحل</span>').
                '</td>'.
                    '<td class="border text-right">'.$product->user->name.'</td>'.
                    '<td class="p-1 border text-right flex">'.
                        '<a href="'.route('restrictions.show', $product->entrie_id).'" class="p-1 rounded-full group transition-all duration-500 flex item-center">'
                        .'<svg class="w-6 h-6 text-[#2430d3] dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">'.
                        '<path stroke="currentColor" stroke-width="2" d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z"/>'.
                        '<path stroke="currentColor" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>'.
                      '</svg>'.
                            '</a>'.
                        '<a href="'.route('restrictions.edit', $product->entrie_id).'" class="p-1 rounded-full group transition-all duration-500 flex item-center">'
                        .'<svg class="w-6 h-6 text-[#2430d3] dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">'
                        .'<svg class="w-6 h-6 text-[#2430d3] dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">'
                        .'<path fill-rule="evenodd" d="M11.32 6.176H5c-1.105 0-2 .949-2 2.118v10.588C3 20.052 3.895 21 5 21h11c1.105 0 2-.948 2-2.118v-7.75l-3.914 4.144A2.46 2.46 0 0 1 12.81 16l-2.681.568c-1.75.37-3.292-1.263-2.942-3.115l.536-2.839c.097-.512.335-.983.684-1.352l2.914-3.086Z" clip-rule="evenodd"/>'
                        .'<path fill-rule="evenodd" d="M19.846 4.318a2.148 2.148 0 0 0-.437-.692 2.014 2.014 0 0 0-.654-.463 1.92 1.92 0 0 0-1.544 0 2.014 2.014 0 0 0-.654.463l-.546.578 2.852 3.02.546-.579a2.14 2.14 0 0 0 .437-.692 2.244 2.244 0 0 0 0-1.635ZM17.45 8.721 14.597 5.7 9.82 10.76a.54.54 0 0 0-.137.27l-.536 2.84c-.07.37.239.696.588.622l2.682-.567a.492.492 0 0 0 .255-.145l4.778-5.06Z" clip-rule="evenodd"/>'
                      .'</svg>'.
                            '</a>'.
                        '<a href="#" class="mt-[10px]   rounded-full  group transition-all duration-500  flex item-center delete-payment" data-id="'.$product->entrie_id.'" >'.
                        '<svg class="" width="18" height="18" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">'.
                        '<path class="fill-red-600" d="M4.00031 5.49999V4.69999H3.20031V5.49999H4.00031ZM16.0003 5.49999H16.8003V4.69999H16.0003V5.49999ZM17.5003 5.49999L17.5003 6.29999C17.9421 6.29999 18.3003 5.94183 18.3003 5.5C18.3003 5.05817 17.9421 4.7 17.5003 4.69999L17.5003 5.49999ZM9.30029 9.24997C9.30029 8.80814 8.94212 8.44997 8.50029 8.44997C8.05847 8.44997 7.70029 8.80814 7.70029 9.24997H9.30029ZM7.70029 13.75C7.70029 14.1918 8.05847 14.55 8.50029 14.55C8.94212 14.55 9.30029 14.1918 9.30029 13.75H7.70029ZM12.3004 9.24997C12.3004 8.80814 11.9422 8.44997 11.5004 8.44997C11.0585 8.44997 10.7004 8.80814 10.7004 9.24997H12.3004ZM10.7004 13.75C10.7004 14.1918 11.0585 14.55 11.5004 14.55C11.9422 14.55 12.3004 14.1918 12.3004 13.75H10.7004ZM4.00031 6.29999H16.0003V4.69999H4.00031V6.29999ZM15.2003 5.49999V12.5H16.8003V5.49999H15.2003ZM11.0003 16.7H9.00031V18.3H11.0003V16.7ZM4.80031 12.5V5.49999H3.20031V12.5H4.80031ZM9.00031 16.7C7.79918 16.7 6.97882 16.6983 6.36373 16.6156C5.77165 16.536 5.49093 16.3948 5.29823 16.2021L4.16686 17.3334C4.70639 17.873 5.38104 18.0979 6.15053 18.2013C6.89702 18.3017 7.84442 18.3 9.00031 18.3V16.7ZM3.20031 12.5C3.20031 13.6559 3.19861 14.6033 3.29897 15.3498C3.40243 16.1193 3.62733 16.7939 4.16686 17.3334L5.29823 16.2021C5.10553 16.0094 4.96431 15.7286 4.88471 15.1366C4.80201 14.5215 4.80031 13.7011 4.80031 12.5H3.20031ZM15.2003 12.5C15.2003 13.7011 15.1986 14.5215 15.1159 15.1366C15.0363 15.7286 14.8951 16.0094 14.7024 16.2021L15.8338 17.3334C16.3733 16.7939 16.5982 16.1193 16.7016 15.3498C16.802 14.6033 16.8003 13.6559 16.8003 12.5H15.2003ZM11.0003 18.3C12.1562 18.3 13.1036 18.3017 13.8501 18.2013C14.6196 18.0979 15.2942 17.873 15.8338 17.3334L14.7024 16.2021C14.5097 16.3948 14.229 16.536 13.6369 16.6156C13.0218 16.6983 12.2014 16.7 11.0003 16.7V18.3ZM2.50031 4.69999C2.22572 4.7 2.04405 4.7 1.94475 4.7C1.89511 4.7 1.86604 4.7 1.85624 4.7C1.85471 4.7 1.85206 4.7 1.851 4.7C1.05253 5.50059 1.85233 6.3 1.85256 6.3C1.85273 6.3 1.85297 6.3 1.85327 6.3C1.85385 6.3 1.85472 6.3 1.85587 6.3C1.86047 6.3 1.86972 6.3 1.88345 6.3C1.99328 6.3 2.39045 6.3 2.9906 6.3C4.19091 6.3 6.2032 6.3 8.35279 6.3C10.5024 6.3 12.7893 6.3 14.5387 6.3C15.4135 6.3 16.1539 6.3 16.6756 6.3C16.9364 6.3 17.1426 6.29999 17.2836 6.29999C17.3541 6.29999 17.4083 6.29999 17.4448 6.29999C17.4631 6.29999 17.477 6.29999 17.4863 6.29999C17.4909 6.29999 17.4944 6.29999 17.4968 6.29999C17.498 6.29999 17.4988 6.29999 17.4994 6.29999C17.4997 6.29999 17.4999 6.29999 17.5001 6.29999C17.5002 6.29999 17.5003 6.29999 17.5003 5.49999C17.5003 4.69999 17.5002 4.69999 17.5001 4.69999C17.4999 4.69999 17.4997 4.69999 17.4994 4.69999C17.4988 4.69999 17.498 4.69999 17.4968 4.69999C17.4944 4.69999 17.4909 4.69999 17.4863 4.69999C17.477 4.69999 17.4631 4.69999 17.4448 4.69999C17.4083 4.69999 17.3541 4.69999 17.2836 4.69999C17.1426 4.7 16.9364 4.7 16.6756 4.7C16.1539 4.7 15.4135 4.7 14.5387 4.7C12.7893 4.7 10.5024 4.7 8.35279 4.7C6.2032 4.7 4.19091 4.7 2.9906 4.7C2.39044 4.7 1.99329 4.7 1.88347 4.7C1.86974 4.7 1.86051 4.7 1.85594 4.7C1.8548 4.7 1.85396 4.7 1.85342 4.7C1.85315 4.7 1.85298 4.7 1.85288 4.7C1.85284 4.7 2.65253 5.49941 1.85408 6.3C1.85314 6.3 1.85296 6.3 1.85632 6.3C1.86608 6.3 1.89511 6.3 1.94477 6.3C2.04406 6.3 2.22573 6.3 2.50031 6.29999L2.50031 4.69999ZM7.05028 5.49994V4.16661H5.45028V5.49994H7.05028ZM7.91695 3.29994H12.0836V1.69994H7.91695V3.29994ZM12.9503 4.16661V5.49994H14.5503V4.16661H12.9503ZM12.0836 3.29994C12.5623 3.29994 12.9503 3.68796 12.9503 4.16661H14.5503C14.5503 2.8043 13.4459 1.69994 12.0836 1.69994V3.29994ZM7.05028 4.16661C7.05028 3.68796 7.4383 3.29994 7.91695 3.29994V1.69994C6.55465 1.69994 5.45028 2.8043 5.45028 4.16661H7.05028ZM2.50031 6.29999C4.70481 6.29998 6.40335 6.29998 8.1253 6.29997C9.84725 6.29996 11.5458 6.29995 13.7503 6.29994L13.7503 4.69994C11.5458 4.69995 9.84724 4.69996 8.12529 4.69997C6.40335 4.69998 4.7048 4.69998 2.50031 4.69999L2.50031 6.29999ZM13.7503 6.29994L17.5003 6.29999L17.5003 4.69999L13.7503 4.69994L13.7503 6.29994ZM7.70029 9.24997V13.75H9.30029V9.24997H7.70029ZM10.7004 9.24997V13.75H12.3004V9.24997H10.7004Z" fill="#F87171"></path>'.
                        ' </svg>'.
                        '</a>'.

                    '</td>'.
                            '</tr>';
                }
                return Response($output);
            }
        }
    }

}
