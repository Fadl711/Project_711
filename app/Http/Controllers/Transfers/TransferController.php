<?php

namespace App\Http\Controllers\Transfers;

use App\Http\Controllers\Controller;
use App\Models\AccountingPeriod;
use App\Models\DailyEntrie;
use App\Models\MainAccount;
use App\Models\SubAccount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransferController extends Controller
{
    //
    public function create(){
        $mainAccounts=MainAccount::all();
        $entries = session('entries',  []); // ستكون فارغة إذا لم تكن موجودة

    // return view('transfer_restrictions.create', compact('entries','mainAccounts'));
        return view('transfer_restrictions.create',['mainAccounts'=>$mainAccounts,'entries'=>     $entries ]);
    }
    public function index(){
        $accountingPeriod=AccountingPeriod::all();
        return view('transfer_restrictions.index',['accountingPeriods'=>$accountingPeriod]);
    }
   
    public function optional(Request $request)
    {
        $the_way_of_deportation = $request->the_way_of_deportation;
        $TypeRestrictions = $request->TypeRestrictions;
        $date = $request->date;
    
        if ($the_way_of_deportation === 'all') {
            if ($error = $this->validateOptional($TypeRestrictions, $date)) {
                return back()->with('error', $error);
            }
    
            // جلب الإدخالات
            $entries = $this->fetchEntries(null, $date, $TypeRestrictions);
            if ($entries->isEmpty()) {
                return back()->with('error', 'لا توجد قيود يومية غير مرحلة.');
            }
    
            // جلب الحساب الرئيسي
            $mainAccount = MainAccount::first(); // أو استخدم الطريقة المناسبة لجلب الحساب الرئيسي
    
            // تمرير جميع المعطيات المطلوبة
            return $this->prepareResponse($entries, $mainAccount, $date, $TypeRestrictions, $request);
        } elseif ($the_way_of_deportation === 'optional') {
            return $this->show($request);
        }
    
        return back()->with('error', 'طريقة الترحيل غير صحيحة.');
    }
    
    public function show(Request $request)
    {
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->firstOrFail();
        $id = $request->main_account_id;
        $subAccountId = $request->sub_account_id;
        $TypeRestrictions = $request->TypeRestrictions;
    
        // التحقق من صحة المدخلات
        $error = $this->validateRequest($id, $subAccountId, $TypeRestrictions, $request->date);
        if ($error) {
            return back()->with('error', $error);
        }
    
        // جلب الحساب الرئيسي
        $mainAccount = MainAccount::findOrFail($id);
    
        // جلب الحسابات الفرعية
        $subAccount = $this->getSubAccountIds($id, $subAccountId);
        Log::info($subAccount); // تسجيل قيمة الحساب الفرعي
    
        if (is_null($subAccount)) {
            return back()->with('error', 'يرجى اختيار حساب فرعي صالح.');
        }
    
        // استعلام جلب القيود اليومية
        $entries = $this->fetchDailyEntries($accountingPeriod->accounting_period_id, $subAccount, $TypeRestrictions, $request->date);
    
        if ($entries->isEmpty()) {
            return back()->with('error', 'لا توجد قيود يومية غير مرحلة.');
        }
    
        // جلب أسماء الحسابات الفرعية المدينة والدائنة
        $debitAccounts = $this->getAccountNames($entries->pluck('account_debit_id')->unique());
        $creditAccounts = $this->getAccountNames($entries->pluck('account_Credit_id')->unique());
       $subAccount= SubAccount::find($subAccountId);
        // تمرير البيانات إلى العرض
        return back()->with([
            'mainAccount' => $mainAccount,
            'entries' => $entries,
            'subAccount' => $subAccount, 
            'debitAccounts' => $debitAccounts,
            'creditAccounts' => $creditAccounts
        ]);
    }
    
    
    private function fetchDailyEntries($accountingPeriodId, $subAccounts, $TypeRestrictions, $date)
    {
        $query = DB::table('daily_entries')
        ->select('entrie_id', 'Amount_debit', 'Amount_Credit', 'account_debit_id', 'account_Credit_id', 'Statement', 'Daily_page_id', 'User_id', 'status', 'status_debit', 'created_at')
        ->where('accounting_period_id', $accountingPeriodId);
    
    // إضافة الحسابات الفرعية إلى الاستعلام
    if ($subAccounts instanceof \Illuminate\Database\Eloquent\Collection) {
        $subAccountIds = $subAccounts->pluck('sub_account_id')->toArray(); // تحويل إلى مصفوفة
        $query->where(function($subQuery) use ($subAccountIds) {
            $subQuery->whereIn('account_debit_id', $subAccountIds)
                     ->orWhereIn('account_Credit_id', $subAccountIds);
        });
    } else {
        $query->where(function ($subQuery) use ($subAccounts) {
            $subQuery->where('account_debit_id', $subAccounts->sub_account_id)
                     ->orWhere('account_Credit_id', $subAccounts->sub_account_id);
        });
    }
    
    // تصفية حسب التاريخ إذا كان النوع 2
    if ($TypeRestrictions == 2 && !empty($date)) {
        $query->whereDate('created_at', '=', $date);
    }
    
    return $query->get();
    
    }
    
    private function getAccountNames($accountIds)
    {
        return DB::table('sub_accounts')->whereIn('sub_account_id', $accountIds)->pluck('sub_name', 'sub_account_id');
    }
    

    private function validateOptional($TypeRestrictions, $date)
    {
        if ($TypeRestrictions == 2 && empty($date)) {
            return 'يرجى تحديد التاريخ عند اختيار النوع 2.';
        }
        return null;
    }

    private function validateRequest($mainAccountId, $subAccountId, $TypeRestrictions, $date)
    {
        if ($mainAccountId === "null") {
            return 'يرجى اختيار حساب رئيسي.';
        }

        if ($subAccountId === "null") {
            return 'يرجى اختيار حساب فرعي.';
        }
        if ($TypeRestrictions == 2 && empty($date)) {
            return 'يرجى تحديد التاريخ عند اختيار النوع 2.';
        }
        return null;
    }
    private function getSubAccountIds($mainAccountId, $subAccountId)
    {
        if ($subAccountId === 'all') {
            // جلب كل الحسابات الفرعية المرتبطة بالحساب الرئيسي كـ مجموعة
            return SubAccount::where('Main_id', $mainAccountId)->get();
        } elseif ($subAccountId >= 1) {
            // جلب الحساب الفرعي المحدد كـ كائن واحد
            return SubAccount::where('sub_account_id', $subAccountId)->first();  // استخدام first() لجلب كائن فردي
        }
        return null;
    }
private function fetchEntries($subAccountIdsCollection, $date, $TypeRestrictions)
{
    $accountingPeriod = AccountingPeriod::where('is_closed', false)->firstOrFail();
//     $accountingPeriod = $accountingPeriod->created_at;
// $accountingPeriodyear = Carbon::now()->format('Y');
// $start_month = Carbon::now()->format('m');
// $today = Carbon::now()->toDateString();
    // $months = DailyEntrie::whereYear('created_at', $accountingPeriodyear)
    // ->whereMonth('created_at', '>=', $start_month)
    // ->whereMonth('created_at', '<=', $today)
    // ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year')
    // ->distinct()
    // ->orderBy('year')
    // ->orderBy('month')
    // ->get();
    // dd( $months);
    $query = DB::table('daily_entries')
        ->select('entrie_id', 'Amount_debit', 'Amount_Credit', 'account_debit_id', 'account_Credit_id', 'Statement', 'Daily_page_id', 'User_id', 'status','status_debit', 'created_at')
        ->where('accounting_period_id', $accountingPeriod->accounting_period_id);
    // إضافة الحسابات الفرعية في الاستعلام
    if ($subAccountIdsCollection) {
        $query->where(function ($subQuery) use ($subAccountIdsCollection) {
            $subQuery->whereIn('account_debit_id', $subAccountIdsCollection->pluck('sub_account_id'))
                     ->orWhereIn('account_Credit_id', $subAccountIdsCollection->pluck('sub_account_id'));
        });
    }
    // احصل على جميع الإدخالات أولاً
    $entries = $query->get(); 
    // تصفية الإدخالات حسب التاريخ إذا كان النوع 2 والتاريخ محدد
    if ($TypeRestrictions == 2 && !empty($date)) {
        try {
            $entries = $entries->filter(function ($entry) use ($date) {
                return \Carbon\Carbon::parse($entry->created_at)->isSameDay($date);
            });
        } catch (\Exception $e) {
            Log::error("خطأ في تصفية التاريخ: " . $e->getMessage());
            return collect(); // إرجاع مجموعة فارغة عند حدوث خطأ
        }
    }

    return $entries; // إرجاع الإدخالات
}

private function prepareResponse($entries, $mainAccount = null, $date, $TypeRestrictions, Request $request)
{
    // جلب معرفات الحسابات المدينة والدائنة
    $debitAccountIds = $entries->pluck('account_debit_id')->unique();
    $creditAccountIds = $entries->pluck('account_Credit_id')->unique();

    // جلب أسماء الحسابات الفرعية المدينة والدائنة
    $debitAccounts = DB::table('sub_accounts')
        ->whereIn('sub_account_id', $debitAccountIds)
        ->pluck('sub_name', 'sub_account_id');

    $creditAccounts = DB::table('sub_accounts')
        ->whereIn('sub_account_id', $creditAccountIds)
        ->pluck('sub_name', 'sub_account_id');

    // جلب الحساب الفرعي المحدد
    $subAccountId = $request->sub_account_id;
    $subAccount = SubAccount::find($subAccountId);

    // إرجاع البيانات للعرض
    return back()->with([
        'entries' => $entries,
        'debitAccounts' => $debitAccounts,
        'creditAccounts' => $creditAccounts,
        'mainAccount' => $mainAccount,
        'subAccount' => $subAccount // التأكد من تضمين الحساب الفرعي
    ]);
}
public function transferEntry(Request $request)
{
    // تحقق من البيانات المدخلة
    $request->validate([
        'entrie_id' => 'required|integer',
    ]);

    try {
        // جلب القيد باستخدام ID
        $entry = DailyEntrie::findOrFail($request->entrie_id);
        // عملية ترحيل القيد
        // (قم بتعديل هذا القسم حسب المنطق الخاص بك)
        $entry->status = 'مرحلة'; // تحديث الحالة
        $entry->save();

        // إرجاع استجابة JSON ناجحة
        return response()->json(['message' => 'تم ترحيل القيد بنجاح!']);
    } catch (\Exception $e) {
        // في حالة حدوث خطأ
        return response()->json(['error' => '222حدث خطأ أثناء ترحيل القيد: ' . $e->getMessage()], 500);
    }
}




      

}
