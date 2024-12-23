<?php

namespace App\Http\Controllers;

use App\Enum\AccountClass;
use App\Models\AccountingPeriod;
use App\Models\Currency;
use App\Models\CurrencySetting;
use App\Models\DailyEntrie;
use App\Models\GeneralEntrie;
use App\Models\GeneralJournal;
use App\Models\MainAccount;
use App\Models\SubAccount;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use NumberToWords\NumberToWords;

class CustomerCoctroller extends Controller
{

    public function index(){

        return view('customers.index');
    }
    public function show(){
 // استعلام لجمع البيانات المدينة والدائنة
 $accountingPeriod = AccountingPeriod::where('is_closed',false)->first();

 $balances = DailyEntrie::selectRaw(
    'sub_accounts.sub_account_id,
     sub_accounts.sub_name,
     sub_accounts.Phone,
     SUM(CASE WHEN daily_entries.account_debit_id = sub_accounts.sub_account_id THEN daily_entries.Amount_debit ELSE 0 END) as total_debit,
     SUM(CASE WHEN daily_entries.account_Credit_id = sub_accounts.sub_account_id THEN daily_entries.Amount_Credit ELSE 0 END) as total_credit'
)
->where('daily_entries.accounting_period_id',$accountingPeriod->accounting_period_id)

->join('sub_accounts', function ($join) {
    $join->on('daily_entries.account_debit_id', '=', 'sub_accounts.sub_account_id')
         ->orOn('daily_entries.account_Credit_id', '=', 'sub_accounts.sub_account_id');
})
->where('sub_accounts.AccountClass', 1) // إضافة شرط AccountClass = 1
->groupBy('sub_accounts.sub_account_id', 'sub_accounts.sub_name', 'sub_accounts.Phone')
->get();

// معالجة البيانات لإضافة الفارق ونوعه
$balances = $balances->map(function ($balance) {
    $difference = $balance->total_debit - $balance->total_credit;
    $balance->difference = $difference;
    $balance->difference_type = $difference > 0 ? 'مدين' : ($difference < 0 ? 'دائن' : 'متوازن');
    return $balance;
});

return view('customers.show', compact('balances'));
    }

    public function createStatement(Request $request, $id)
    {
        $validated = $request->validate([
            'list' => 'nullable|string',
            'listradio' => 'nullable|string',
            'accountlistradio' => 'nullable|string|max:255',
        ]);
        if ($validated['list'] === "FullDisclosureOfAccounts") 
        {
     return $this->FullDisclosureOfAccounts($id,$validated);
        }

        if ($validated['list'] === "FullDisclosureOfSubAccounts") 
        {
            if ($validated['accountlistradio'] === "mainAccount") 
            {
            return $this->FullDisclosureOfSubAccounts($id,$validated);
        }
        if ($validated['accountlistradio'] === "subAccount") 
        {
          
        return $this->FullDisclosureOfSubAccounts($id,$validated);
        
    }
        }
        if ($validated['list'] === "Disclosure_of_all_sub_accounts_after_migration") 
        {
            return $this->Disclosure_of_all_sub_accounts_after_migration($request, $id);

        }
       
        if ($validated['accountlistradio'] === "subAccount") 
        {
            if ($validated['list'] === "summary") 
            {
                return $this->showStatementSubAccountTotally($validated['listradio'], $id);

            }
            elseif ($validated['list'] === "detail")
            {
                return $this->showStatementSubAccountMyanalysis($validated['listradio'], $id);
                
            }
        } 
        if ($validated['accountlistradio'] === "mainAccount") 
        {
            if ($validated['list'] === "summary") 
            {
                return $this->showStatementMainAccountTotally($validated['listradio'], $id);
            }
            elseif ($validated['list'] === "detail")
            {
                return $this->getDailyEntriesMainAccountMyanalysis($validated['listradio'], $id);
            }
        }

    }
    public function Disclosure_of_all_sub_accounts_after_migration($validated,$id)
    {
        $Myanalysis="كلي";
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
        $idCurr=1;
        $currencysettings=$curre->currency_name ?? 'ريال يمني';
        $curre=CurrencySetting::where('currency_settings_id',$idCurr)->first(); 
        $UserName = User::where('id',auth()->user()->id,)->pluck('name')->first(); 
     if($$request->list)
     {
        
     }
        $balances = SubAccount::where('sub_account_id',$id)->first(); 
        $idaccounn=$balances->sub_account_id;
        $total_debit = GeneralEntrie::where('sub_id',$balances->sub_account_id)->sum('amount');
           
      

// $SumDebtor_amount = $balances->sum('total_debit');
// $Sale_priceSum = abs($SumDebtor_amount );

// $numberToWords = new NumberToWords();
// $numberTransformer = $numberToWords->getNumberTransformer('ar'); // اللغة العربية
// $priceInWords=is_numeric($Sale_priceSum) 
// ? $numberTransformer->toWords( abs($SumDebtor_amount )) . ' ' . $currencysettings
// : 'القيمة غير صالحة';
$accountClasses = [
1 => 'الحساب',
2 => 'الحساب',
3 => 'الحساب',
4 => 'الحساب',
5 => 'الحساب',
];
$AccountClassName ='الحساب';
dd(  $balances );  
$Myanalysis=" نهائي لكل لحسابات  قبل الترحيل";

return view('report.Final-full-disclosure', compact('Myanalysis','balances','AccountClassName','currencysettings','UserName','accountingPeriod','SumCredit_amount','SumDebtor_amount',
'priceInWords','startDate','endDate','Sale_priceSum'))->render(); // إرجاع المحتوى كـ HTML

    }
    private function FullDisclosureOfAccounts($validated)
    {
        $Myanalysis="كلي";
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
        $idCurr=1;
        $currencysettings=$curre->currency_name ?? 'ريال يمني';
        $curre=CurrencySetting::where('currency_settings_id',$idCurr)->first(); 
        $UserName = User::where('id',auth()->user()->id,)->pluck('name')->first(); 
        // dd($validated['accountlistradio'] );
     
        // $customerMainAccount = MainAccount::where('main_account_id', $id)->first();
        // $idaccounn=$customerMainAccount->main_account_id;
        $balances = DailyEntrie::selectRaw(
            'sub_accounts.sub_account_id,
             sub_accounts.sub_name,
             sub_accounts.Phone,
             SUM(CASE WHEN daily_entries.account_debit_id = sub_accounts.sub_account_id THEN daily_entries.Amount_debit ELSE 0 END) as total_debit,
             SUM(CASE WHEN daily_entries.account_Credit_id = sub_accounts.sub_account_id THEN daily_entries.Amount_Credit ELSE 0 END) as total_credit'
        )
        ->where('daily_entries.accounting_period_id',$accountingPeriod->accounting_period_id)
        
        ->join('sub_accounts', function ($join) {
            $join->on('daily_entries.account_debit_id', '=', 'sub_accounts.sub_account_id')
                 ->orOn('daily_entries.account_Credit_id', '=', 'sub_accounts.sub_account_id');
        })
        ->groupBy('sub_accounts.sub_account_id', 'sub_accounts.sub_name', 'sub_accounts.Phone')
        ->get();
    
            $startDate = $accountingPeriod->created_at?->format('Y-m-d') ?? 'غير متوفر';
            $endDate = now()->toDateString();

    // معالجة البيانات لإضافة الفارق ونوعه
    $balances = $balances->map(function ($balance) {
        $difference = $balance->total_debit - $balance->total_credit;
        $balance->difference = $difference;
        $balance->difference_type = $difference > 0 ? 'مدين' : ($difference < 0 ? 'دائن' : 'متوازن');
        return $balance;
    });
    
    $SumDebtor_amount = $balances->sum('total_debit');
    $SumCredit_amount = $balances->sum('total_credit');    
    $Sale_priceSum = abs($SumDebtor_amount - $SumCredit_amount);
    
   $numberToWords = new NumberToWords();
    $numberTransformer = $numberToWords->getNumberTransformer('ar'); // اللغة العربية
    $priceInWords=is_numeric($Sale_priceSum) 
    ? $numberTransformer->toWords( abs($SumDebtor_amount - $SumCredit_amount)) . ' ' . $currencysettings
    : 'القيمة غير صالحة';
$accountClasses = [
1 => 'الحساب',
2 => 'الحساب',
3 => 'الحساب',
4 => 'الحساب',
5 => 'الحساب',
];
$AccountClassName ='الحساب';
// dd(  $Sale_priceSum );  
$Myanalysis=" نهائي لكل لحسابات  قبل الترحيل";

return view('report.Final-full-disclosure', compact('Myanalysis','balances','AccountClassName','currencysettings','UserName','accountingPeriod','SumCredit_amount','SumDebtor_amount',
'priceInWords','startDate','endDate','Sale_priceSum'))->render(); // إرجاع المحتوى كـ HTML

    }
  
    private function FullDisclosureOfSubAccounts($id,$validated)
    {
        $Myanalysis="كلي";
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
        $idCurr=1;
        $currencysettings=$curre->currency_name ?? 'ريال يمني';
        $curre=CurrencySetting::where('currency_settings_id',$idCurr)->first(); 
        $UserName = User::where('id',auth()->user()->id,)->pluck('name')->first(); 
        // dd($validated['accountlistradio'] );
       
        if ($validated['accountlistradio'] === "subAccount") 
        {
         
            $customerMainAccount = SubAccount::where('sub_account_id',$id)->first(); 
            $idaccounn=$customerMainAccount->sub_account_id;
            $balances = DailyEntrie::selectRaw(
                'sub_accounts.sub_account_id,
                 sub_accounts.sub_name,
                 sub_accounts.Phone,
                 SUM(CASE WHEN daily_entries.account_debit_id = sub_accounts.sub_account_id THEN daily_entries.Amount_debit ELSE 0 END) as total_debit,
                 SUM(CASE WHEN daily_entries.account_Credit_id = sub_accounts.sub_account_id THEN daily_entries.Amount_Credit ELSE 0 END) as total_credit'
            )
            ->where('daily_entries.accounting_period_id',$accountingPeriod->accounting_period_id)
            
            ->join('sub_accounts', function ($join) {
                $join->on('daily_entries.account_debit_id', '=', 'sub_accounts.sub_account_id')
                     ->orOn('daily_entries.account_Credit_id', '=', 'sub_accounts.sub_account_id');
            })
            ->where('sub_accounts.sub_account_id',$idaccounn) // إضافة شرط AccountClass = 1
            ->groupBy('sub_accounts.sub_account_id', 'sub_accounts.sub_name', 'sub_accounts.Phone')
            ->get();
        }
        if ($validated['accountlistradio'] === "mainAccount") 
        {
            $customerMainAccount = MainAccount::where('main_account_id', $id)->first();
            $idaccounn=$customerMainAccount->main_account_id;
            $balances = DailyEntrie::selectRaw(
                'sub_accounts.sub_account_id,
                 sub_accounts.sub_name,
                 sub_accounts.Phone,
                 SUM(CASE WHEN daily_entries.account_debit_id = sub_accounts.sub_account_id THEN daily_entries.Amount_debit ELSE 0 END) as total_debit,
                 SUM(CASE WHEN daily_entries.account_Credit_id = sub_accounts.sub_account_id THEN daily_entries.Amount_Credit ELSE 0 END) as total_credit'
            )
            ->where('daily_entries.accounting_period_id',$accountingPeriod->accounting_period_id)
            
            ->join('sub_accounts', function ($join) {
                $join->on('daily_entries.account_debit_id', '=', 'sub_accounts.sub_account_id')
                     ->orOn('daily_entries.account_Credit_id', '=', 'sub_accounts.sub_account_id');
            })
            ->where('sub_accounts.Main_id',$idaccounn)
            ->groupBy('sub_accounts.sub_account_id', 'sub_accounts.sub_name', 'sub_accounts.Phone')
            ->get();
        }
                $startDate = $accountingPeriod->created_at?->format('Y-m-d') ?? 'غير متوفر';
                $endDate = now()->toDateString();
       // معالجة البيانات لإضافة الفارق ونوعه
        $balances = $balances->map(function ($balance) {
            $difference = $balance->total_debit - $balance->total_credit;
            $balance->difference = $difference;
            $balance->difference_type = $difference > 0 ? 'مدين' : ($difference < 0 ? 'دائن' : 'متوازن');
            return $balance;
        });
        
        $SumDebtor_amount = $balances->sum('total_debit');
        $SumCredit_amount = $balances->sum('total_credit');    
    
        $Sale_priceSum = abs($SumDebtor_amount - $SumCredit_amount);
        $SumAmount = $SumDebtor_amount - $SumCredit_amount;
        
       $numberToWords = new NumberToWords();
        $numberTransformer = $numberToWords->getNumberTransformer('ar'); // اللغة العربية
        $priceInWords=is_numeric($Sale_priceSum) 
        ? $numberTransformer->toWords( abs($SumDebtor_amount - $SumCredit_amount)) . ' ' . $currencysettings
        : 'القيمة غير صالحة';
   $accountClasses = [
    1 => 'الحساب',
    2 => 'الحساب',
    3 => 'الحساب',
    4 => 'الحساب',
    5 => 'الحساب',
];
$AccountClassName = $accountClasses[$customerMainAccount->AccountClass] ?? 'غير معروف';
// dd(  $Sale_priceSum );  
$Myanalysis=" نهائي للحسابات الفرعية قبل الترحيل";

   return view('report.Final-full-disclosure', compact('Myanalysis','SumAmount','balances','AccountClassName','currencysettings','UserName','accountingPeriod','SumCredit_amount','SumDebtor_amount',
   'priceInWords','startDate','endDate','customerMainAccount','Sale_priceSum'))->render(); // إرجاع المحتوى كـ HTML



    }

    public function showStatementMainAccountTotally($validated, $id)
    {
        $Myanalysis="كلي";
        try {
            // التحقق من الحساب الرئيسي
            $customer = MainAccount::where('main_account_id', $id)->first();
            if (!$customer) {
                return response()->json(['error' => 'الحساب الرئيسي غير موجود'], 404);
            }
    
            // الحصول على الفترة المحاسبية المفتوحة
            $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
    
            $query = DailyEntrie::with(['debitAccount', 'debitAccount.mainAccount', 'creditAccount', 'creditAccount.mainAccount'])
            ->selectRaw(
                'daily_entries.account_debit_id,
                 daily_entries.daily_entries_type,
                 daily_entries.account_Credit_id,
                 daily_entries.Invoice_type,
                 daily_entries.Invoice_id,
                 daily_entries.Statement,
                 daily_entries.entrie_id,
                 daily_entries.created_at,
                 sub_accounts.sub_account_id,
                 main_accounts.main_account_id,
                 SUM(CASE WHEN daily_entries.account_debit_id = sub_accounts.sub_account_id THEN daily_entries.Amount_debit ELSE 0 END) as total_debit,
                 SUM(CASE WHEN daily_entries.account_Credit_id = sub_accounts.sub_account_id THEN daily_entries.Amount_Credit ELSE 0 END) as total_credit'
            )
            ->join('sub_accounts', function ($join) {
                $join->on('daily_entries.account_debit_id', '=', 'sub_accounts.sub_account_id')
                     ->orOn('daily_entries.account_Credit_id', '=', 'sub_accounts.sub_account_id');
            })
            ->join('main_accounts', 'sub_accounts.Main_id', '=', 'main_accounts.main_account_id')
            ->where('main_accounts.main_account_id', $id)
            ->groupBy(
                'sub_accounts.sub_account_id',
                'main_accounts.main_account_id',
                'daily_entries.account_debit_id',
                'daily_entries.Invoice_type',
                'daily_entries.account_Credit_id',
                'daily_entries.daily_entries_type',
                'daily_entries.Statement',
                'daily_entries.entrie_id',
                'daily_entries.Invoice_id',
                'daily_entries.created_at'
            )
            ->orderBy('created_at', 'asc'); // تصحيح ترتيب النتائج
            $query->where('daily_entries.accounting_period_id',$accountingPeriod->accounting_period_id);

            $startDate = null;
            $endDate = null;
            
            // تخصيص الفترة الزمنية بناءً على المدخلات
            switch ($validated ?? '') {
                case '2': // اليوم
                    $startDate = now()->toDateString();
                    $endDate = now()->toDateString();
                    $query->whereDate('daily_entries.created_at', $startDate);
                    break;
                case '3': // هذا الأسبوع
                    $startDate = now()->startOfWeek()->toDateString();
                    $endDate = now()->endOfWeek()->toDateString();
                    $query->whereBetween('daily_entries.created_at', [$startDate, $endDate]);
                    break;
                case '4': // هذا الشهر
                    $startDate = now()->startOfMonth()->toDateString();
                    $endDate = now()->endOfMonth()->toDateString();
                    $query->whereMonth('daily_entries.created_at', now()->month)
                          ->whereYear('daily_entries.created_at', now()->year);
                    break;
                default: // عرض كل القيود
                    $startDate = $accountingPeriod->created_at?->format('Y-m-d') ?? 'غير متوفر';
                    $endDate = now()->toDateString();
                    break;
            }
    
            // جلب القيود اليومية مع الإجماليات
            $entriesTotally = $query->get();
    
            // حساب الإجماليات (بناءً على البيانات المسترجعة)
            $SumDebtor_amount = $entriesTotally->sum('total_debit');
            $SumCredit_amount = $entriesTotally->sum('total_credit');
    
            // حساب الفرق (الربح/الخسارة)
            $Sale_priceSum = abs($SumDebtor_amount - $SumCredit_amount);
    
            // تحويل القيمة إلى كلمات
            $numberToWords = new NumberToWords();
            $numberTransformer = $numberToWords->getNumberTransformer('ar'); // اللغة العربية
            $currencySetting = CurrencySetting::find(1);
            $currencyName = $currencySetting->currency_name ?? 'ريال يمني';
    
            $priceInWords = is_numeric($Sale_priceSum)
                ? $numberTransformer->toWords($Sale_priceSum) . ' ' . $currencyName
                : 'القيمة غير صالحة';
                $accountClasses = [
                    1 => 'العميل',
                    2 => 'المورد',
                    3 => 'المخزن',
                    5 => 'الصندوق',
                ];
                $AccountClassName = $accountClasses[$customer->AccountClass] ?? 'غير معروف';

                $AccountClassName ='';
                $UserName = User::where('id',auth()->user()->id,)->pluck('name')->first();


            // إرجاع العرض
            return view('customers.statement', compact(
                'startDate', 'endDate',
                'customer',
                'Myanalysis',
                'entriesTotally',
                'currencyName',
                'accountingPeriod',
                'SumCredit_amount',
                'SumDebtor_amount',
                'priceInWords',
                'AccountClassName',
                'UserName',
                'Sale_priceSum'

            ));
    
        } catch (\Exception $e) {
            // التعامل مع الأخطاء
            return response()->json(['error' => 'حدث خطأ أثناء معالجة الطلب: ' . $e->getMessage()], 500);
        }
    }
    public function showStatementSubAccountTotally($validated, $id)
    {
        $Myanalysis="كلي";

        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
        $customer = SubAccount::where('sub_account_id',$id)->first(); // استرجاع بيانات العميل
        $idCurr=1;
        $curre=CurrencySetting::where('currency_settings_id',$idCurr)->first(); 
        $UserName = User::where('id',auth()->user()->id,)->pluck('name')->first();    
        $currencysettings=$curre->currency_name ?? 'ريال يمني';
    
     
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
        ->where('sub_accounts.sub_account_id', $id); // إضافة الشرط للحساب الفرعي
        $query->where('daily_entries.accounting_period_id',$accountingPeriod->accounting_period_id);
        $startDate = null;
$endDate = null;

      // تخصيص الفترة الزمنية بناءً على المدخلات
      switch ($validated) {
        case '2': // اليوم
            $startDate = now()->toDateString();
            $endDate = now()->toDateString();
            $query->whereDate('daily_entries.created_at', $startDate);
            break;
        case '3': // هذا الأسبوع
            $startDate = now()->startOfWeek()->toDateString();
            $endDate = now()->endOfWeek()->toDateString();
            $query->whereBetween('daily_entries.created_at', [$startDate, $endDate]);
            break;
        case '4': // هذا الشهر
            $startDate = now()->startOfMonth()->toDateString();
            $endDate = now()->endOfMonth()->toDateString();
            $query->whereMonth('daily_entries.created_at', now()->month)
                  ->whereYear('daily_entries.created_at', now()->year);
            break;
        default: // عرض كل القيود
            $startDate = $accountingPeriod->created_at?->format('Y-m-d') ?? 'غير متوفر';
            $endDate = now()->toDateString();
            break;
    }
                                    // جلب القيود اليومية مع الإجماليات
                                    $entriesTotally = $query->get();
                             
                                                        
                                    // حساب الإجماليات (بناءً على البيانات المسترجعة)
                                    $SumDebtor_amount = $entriesTotally->sum('total_debit');
                                    $SumCredit_amount = $entriesTotally->sum('total_credit');    
                                
                                    $Sale_priceSum = abs($SumDebtor_amount - $SumCredit_amount);
                                
                                   $numberToWords = new NumberToWords();
                                    $numberTransformer = $numberToWords->getNumberTransformer('ar'); // اللغة العربية
                                    $priceInWords=is_numeric($Sale_priceSum) 
                                    ? $numberTransformer->toWords( abs($SumDebtor_amount - $SumCredit_amount)) . ' ' . $currencysettings
                                    : 'القيمة غير صالحة';
                               $accountClasses = [
                                1 => 'العميل',
                                2 => 'المورد',
                                3 => 'المخزن',
                                4 => 'الحساب',
                                5 => 'الصندوق',
                            ];
                            $AccountClassName = $accountClasses[$customer->AccountClass] ?? 'غير معروف';
        
      
                            return view('customers.statement', compact('startDate', 'endDate',
'customer','Myanalysis', 'entriesTotally','AccountClassName','currencysettings','UserName','accountingPeriod','SumCredit_amount','SumDebtor_amount','priceInWords','Sale_priceSum'))->render(); // إرجاع المحتوى كـ HTML


}
    public function showStatementSubAccountMyanalysis($validated, $id)
{
    $Myanalysis="تحليلي";

    $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
    $customer = SubAccount::where('sub_account_id',$id)->first(); // استرجاع بيانات العميل
    $idCurr=1;
    $curre=CurrencySetting::where('currency_settings_id',$idCurr)->first(); 
    $UserName = User::where('id',auth()->user()->id,)->pluck('name')->first();    
    $currencysettings=$curre->currency_name ?? 'ريال يمني';

 
    $query = DailyEntrie::with(['debitAccount', 'debitAccount.mainAccount', 'creditAccount', 'creditAccount.mainAccount'])
    ->selectRaw(
        'daily_entries.account_debit_id,
         daily_entries.daily_entries_type,
         daily_entries.account_Credit_id,
         daily_entries.Invoice_type,
         daily_entries.Invoice_id,
         daily_entries.Statement,
         daily_entries.entrie_id,
         daily_entries.created_at,
  
         SUM(CASE WHEN daily_entries.account_debit_id = sub_accounts.sub_account_id THEN daily_entries.Amount_debit ELSE 0 END) as total_debit,
         SUM(CASE WHEN daily_entries.account_Credit_id = sub_accounts.sub_account_id THEN daily_entries.Amount_Credit ELSE 0 END) as total_credit'
    )
    ->join('sub_accounts', function ($join) {
        $join->on('daily_entries.account_debit_id', '=', 'sub_accounts.sub_account_id')
             ->orOn('daily_entries.account_Credit_id', '=', 'sub_accounts.sub_account_id');
    })
    ->where('sub_accounts.sub_account_id', $id) // إضافة الشرط للحساب الفرعي
    ->groupBy(
    
        'daily_entries.account_debit_id',
        'daily_entries.Invoice_type',
        'daily_entries.account_Credit_id',
        'daily_entries.daily_entries_type',
        'daily_entries.Statement',
        'daily_entries.entrie_id',
        'daily_entries.Invoice_id',
        'daily_entries.created_at'
    )
    ->orderBy('daily_entries.created_at', 'asc'); // تصحيح ترتيب النتائج
    $query->where('daily_entries.accounting_period_id',$accountingPeriod->accounting_period_id);
    $startDate = null;
    $endDate = null;
          // تخصيص الفترة الزمنية بناءً على المدخلات
          switch ($validated ?? '') {
            case '2': // اليوم
                $startDate = now()->toDateString();
                $endDate = now()->toDateString();
                $query->whereDate('daily_entries.created_at', $startDate);
                break;
            case '3': // هذا الأسبوع
                $startDate = now()->startOfWeek()->toDateString();
                $endDate = now()->endOfWeek()->toDateString();
                $query->whereBetween('daily_entries.created_at', [$startDate, $endDate]);
                break;
            case '4': // هذا الشهر
                $startDate = now()->startOfMonth()->toDateString();
                $endDate = now()->endOfMonth()->toDateString();
                $query->whereMonth('daily_entries.created_at', now()->month)
                      ->whereYear('daily_entries.created_at', now()->year);
                break;
            default: // عرض كل القيود
                $startDate = $accountingPeriod->created_at?->format('Y-m-d') ?? 'غير متوفر';
                $endDate = now()->toDateString();
                break;
        }
                                // جلب القيود اليومية مع الإجماليات
                                $entries = $query->get();
                         
                                                    
                                // حساب الإجماليات (بناءً على البيانات المسترجعة)
                                $SumDebtor_amount = $entries->sum('total_debit');
                                $SumCredit_amount = $entries->sum('total_credit');    
                            
                            $AccountClassName = $accountClasses[$customer->AccountClass] ?? 'غير معروف';
                                $Sale_priceSum = abs($SumDebtor_amount - $SumCredit_amount);
                            
                               $numberToWords = new NumberToWords();
                                $numberTransformer = $numberToWords->getNumberTransformer('ar'); // اللغة العربية
                                $priceInWords=is_numeric($Sale_priceSum) 
                                ? $numberTransformer->toWords( abs($SumDebtor_amount - $SumCredit_amount)) . ' ' . $currencysettings
                                : 'القيمة غير صالحة';
                           $accountClasses = [
                            1 => 'العميل',
                            2 => 'المورد',
                            3 => 'المخزن',
                            4 => 'الحساب',
                            5 => 'الصندوق',
                        ];
                        $AccountClassName = $accountClasses[$customer->AccountClass] ?? 'غير معروف';
    
                           return view('customers.statement', compact('customer','startDate', 'endDate','Myanalysis', 'entries','AccountClassName','currencysettings','UserName','accountingPeriod','SumCredit_amount','SumDebtor_amount','priceInWords','Sale_priceSum'))->render(); // إرجاع المحتوى كـ HTML
                        }
                    
                        public function getDailyEntriesMainAccountMyanalysis($validated, $id)
                        {
                            $Myanalysis="تحليلي";

                            try {
                                // التحقق من الحساب الرئيسي
                                $customer = MainAccount::where('main_account_id', $id)->first();
                                if (!$customer) {
                                    return response()->json(['error' => 'الحساب الرئيسي غير موجود'], 404);
                                }
                        
                                // الحصول على الفترة المحاسبية المفتوحة
                                $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
                        
                                $query = DailyEntrie::with(['debitAccount', 'debitAccount.mainAccount', 'creditAccount', 'creditAccount.mainAccount'])
                                ->selectRaw(
                                    'daily_entries.account_debit_id,
                                     daily_entries.daily_entries_type,
                                     daily_entries.account_Credit_id,
                                     daily_entries.Invoice_type,
                                     daily_entries.Invoice_id,
                                     daily_entries.Statement,
                                     daily_entries.entrie_id,
                                     daily_entries.created_at,
                                     sub_accounts.sub_account_id,
                                     main_accounts.main_account_id,
                                     SUM(CASE WHEN daily_entries.account_debit_id = sub_accounts.sub_account_id THEN daily_entries.Amount_debit ELSE 0 END) as total_debit,
                                     SUM(CASE WHEN daily_entries.account_Credit_id = sub_accounts.sub_account_id THEN daily_entries.Amount_Credit ELSE 0 END) as total_credit'
                                )
                                ->join('sub_accounts', function ($join) {
                                    $join->on('daily_entries.account_debit_id', '=', 'sub_accounts.sub_account_id')
                                         ->orOn('daily_entries.account_Credit_id', '=', 'sub_accounts.sub_account_id');
                                })
                                ->join('main_accounts', 'sub_accounts.Main_id', '=', 'main_accounts.main_account_id')
                                ->where('main_accounts.main_account_id', $id)
                                ->groupBy(
                                    'sub_accounts.sub_account_id',
                                    'main_accounts.main_account_id',
                                    'daily_entries.account_debit_id',
                                    'daily_entries.Invoice_type',
                                    'daily_entries.account_Credit_id',
                                    'daily_entries.daily_entries_type',
                                    'daily_entries.Statement',
                                    'daily_entries.entrie_id',
                                    'daily_entries.Invoice_id',
                                    'daily_entries.created_at'
                                )
                                ->orderBy('created_at', 'asc'); // تصحيح ترتيب النتائج
                                $query->where('daily_entries.accounting_period_id',$accountingPeriod->accounting_period_id);

                                // dd($validated );
                                switch ($validated ?? '') {
                                    case '2': // اليوم
                                        $startDate = now()->toDateString();
                                        $endDate = now()->toDateString();
                                        $query->whereDate('daily_entries.created_at', $startDate);
                                        break;
                                    case '3': // هذا الأسبوع
                                        $startDate = now()->startOfWeek()->toDateString();
                                        $endDate = now()->endOfWeek()->toDateString();
                                        $query->whereBetween('daily_entries.created_at', [$startDate, $endDate]);
                                        break;
                                    case '4': // هذا الشهر
                                        $startDate = now()->startOfMonth()->toDateString();
                                        $endDate = now()->endOfMonth()->toDateString();
                                        $query->whereMonth('daily_entries.created_at', now()->month)
                                              ->whereYear('daily_entries.created_at', now()->year);
                                        break;
                                    default: // عرض كل القيود
                                        $startDate = $accountingPeriod->created_at?->format('Y-m-d') ?? 'غير متوفر';
                                        $endDate = now()->toDateString();
                                        break;
                                }
                                
                                // جلب القيود اليومية مع الإجماليات
                                $entries = $query->get();
                        
                                // حساب الإجماليات (بناءً على البيانات المسترجعة)
                                $SumDebtor_amount = $entries->sum('total_debit');
                                $SumCredit_amount = $entries->sum('total_credit');
                        
                                // حساب الفرق (الربح/الخسارة)
                                $Sale_priceSum = abs($SumDebtor_amount - $SumCredit_amount);
                        
                                // تحويل القيمة إلى كلمات
                                $numberToWords = new NumberToWords();
                                $numberTransformer = $numberToWords->getNumberTransformer('ar'); // اللغة العربية
                                $currencySetting = CurrencySetting::find(1);
                                $currencyName = $currencySetting->currency_name ?? 'ريال يمني';
                        
                                $priceInWords = is_numeric($Sale_priceSum)
                                    ? $numberTransformer->toWords($Sale_priceSum) . ' ' . $currencyName
                                    : 'القيمة غير صالحة';
                                    $accountClasses = [
                                        1 => 'العميل',
                                        2 => 'المورد',
                                        3 => 'المخزن',
                                        4 => 'الحساب',
                                        5 => 'الصندوق',
                                    ];
                                    $AccountClassName ='الحساب';
                                    $UserName = User::where('id',auth()->user()->id,)->pluck('name')->first();

                                 
                                // إرجاع العرض
                                return view('customers.statement', compact(
                                    'startDate', 'endDate',
                                    'customer',
                                    'Myanalysis',
                                    'entries',
                                    'currencyName',
                                    'accountingPeriod',
                                    'SumCredit_amount',
                                    'SumDebtor_amount',
                                    'priceInWords',
                                    'AccountClassName',
                                    'UserName',
                                    'Sale_priceSum'

                                ));
                        
                            } catch (\Exception $e) {
                                // التعامل مع الأخطاء
                                return response()->json(['error' => 'حدث خطأ أثناء معالجة الطلب: ' . $e->getMessage()], 500);
                            }
                        }
                        
                            public function create(){

        return view('customers.create');
    }
    public function convertArabicToEnglish($number)
    {
        // استبدال الأرقام العربية بما يعادلها من الإنجليزية
        $arabicNumbers = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        return str_replace($arabicNumbers, $englishNumbers, $number);
    }
    public function store(Request $request)
    {
        $User_id=auth()->user()->id;

        $debtor_amount = $request->input('debtor_amount', '٠١٢٣٤٥٦٧٨٩');
        $creditor_amount = $request->input('creditor_amount', '٠١٢٣٤٥٦٧٨٩');
        $Phone1 = $this->convertArabicToEnglish($request->input('Phone', '٠١٢٣٤٥٦٧٨٩'));
        $sub_name = $request->sub_name;

        $mainAccount=MainAccount::where('AccountClass',AccountClass::CUSTOMER->value)->first();
        if (!$mainAccount) {
            return response()->json(['success' => false, 'message' => 'لا يوجد حساب رئيسي للعميل']);
        }

        $account_names_exist = SubAccount::where('Main_id', $mainAccount->main_account_id)->pluck('sub_name');
        if ($account_names_exist->contains($sub_name)) {
            return response()->json(['success' => false, 'message' => 'يوجد نفس هذا الاسم من قبل']);
        }
        $DataSubAccount=new SubAccount();
        $DataSubAccount->Main_id=$mainAccount->main_account_id;
        $DataSubAccount->sub_name=$sub_name;
        $DataSubAccount->AccountClass =$mainAccount->AccountClass;
        $DataSubAccount->typeAccount =$mainAccount->typeAccount;
        $DataSubAccount-> User_id= $User_id;
        $DataSubAccount->debtor_amount = !empty($debtor_amount) ? $debtor_amount :0;
        $DataSubAccount-> creditor_amount= !empty($creditor_amount ) ? $creditor_amount :0;
        $DataSubAccount->Phone = ($Phone1 ) ;
        $DataSubAccount-> name_The_known= $request->name_The_known ?? null ;
        $DataSubAccount->Known_phone =  null ;
        $DataSubAccount->save();
        $DSubAccount = SubAccount::where('sub_account_id', $DataSubAccount->sub_account_id)->first();

      
        $account_debit_id=null;
        $account_Credit_id=null;
        if ($DSubAccount->debtor_amount > 0 || $DSubAccount->creditor_amount > 0) {
           
          
            if($DSubAccount->debtor_amount>0 )
            {
                $account_debit_id=$DSubAccount->sub_account_id;
            }
            if($DSubAccount->creditor_amount>0 )
            {
                $account_Credit_id=$DSubAccount->sub_account_id;
            }
            $accountingPeriod = AccountingPeriod::where('is_closed', false)->firstOrFail();
           
                $today = Carbon::now()->toDateString();
                $dailyPage = GeneralJournal::whereDate('created_at', $today)->first() ?? GeneralJournal::create([]);
        
               
                $transaction_type="رصيد افتتاحي";
                  // إعداد بيانات الإدخالات اليومية
          
                  $Getentrie_id = DailyEntrie::where('Invoice_id',$DSubAccount->sub_account_id)
                  ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
                  ->where('daily_entries_type',$transaction_type)
                      ->value('entrie_id');
               // إنشاء أو تحديث الإدخالات اليومية
            $dailyEntrie = DailyEntrie::updateOrCreate(
                [
                    'entrie_id'=> $Getentrie_id,
                    'accounting_period_id' => $accountingPeriod->accounting_period_id,
                    'Invoice_id' =>  $DSubAccount->sub_account_id,
                    'daily_entries_type' =>$transaction_type,
                ],
                [
                    'account_debit_id' => $account_debit_id,
                    'Amount_Credit' => $DSubAccount->creditor_amount ?: 0,
                    'Amount_debit' => $DSubAccount->debtor_amount ?: 0,
                    'account_Credit_id' => $account_Credit_id,
                    'Statement' => 'فاتورة '." ".'رصيد افتتاحي',
                    'Daily_page_id' => $dailyPage->page_id,
                    'Invoice_type' => 5,
                    'Currency_name' => 'ر',
                    'User_id' =>auth()->user()->id,
                    'status_debit' => 'غير مرحل',
                    'status' => 'غير مرحل',
                ]
            );
            return response()->json(['message' => ' تم حفظ بنجاح ودخال مبلغ للحساب', 'DataSubAccount' => $mainAccount], 201);
        }  
          
        return response()->json(['success' => true, 'message' => 'تمت العملية بنجاح', 'DataSubAccount' => $mainAccount], 201);
    
    }


}
