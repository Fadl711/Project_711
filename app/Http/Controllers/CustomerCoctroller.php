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
use Illuminate\Support\Facades\DB;
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
            'fromDate' => 'nullable',
            'toDate' => 'nullable',
        ]);
        if ($validated['list'] === "FullDisclosureOfAccounts") 
        {
     return $this->FullDisclosureOfAccounts($validated,$id);
        }

        if ($validated['list'] === "FullDisclosureOfSubAccounts") 
        {
            return $this->FullDisclosureOfAccounts($validated,$id);
        }
        if ($validated['list'] === "Full_disclosure_of_accounts_after_migration") 
        {
            return $this->Full_disclosure_of_accounts_after_migration($request, $id);

        }
        if ($validated['list'] === "Disclosure_of_all_sub_accounts_after_migration") 
        {
            return $this->Disclosure_of_all_sub_accounts_after_migration($request, $id);

        }
       
        if ($validated['accountlistradio'] === "subAccount") 
        {
            if ($validated['list'] === "summary") 
            {
                return $this->showStatementSubAccountTotally($validated, $id);

            }
            elseif ($validated['list'] === "detail")
            {
                return $this->showStatementSubAccountMyanalysis($validated, $id);
                
            }
        } 
        if ($validated['accountlistradio'] === "mainAccount") 
        {
            if ($validated['list'] === "summary") 
            {
                return $this->showStatementMainAccountTotally($validated, $id);
            }
         if ($validated['list'] === "detail")

            {
                return $this->getDailyEntriesMainAccountMyanalysis($validated, $id);

                // return $this->getDailyEntriesMainAccountMyanalysis($validated, $id);
            }
        }

    }
    public function  Disclosure_of_all_sub_accounts_after_migration($validated, $id)
    {

        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
        $idCurr=1;
        $currencysettings=$curre->currency_name ?? 'ريال يمني';
        $curre=CurrencySetting::where('currency_settings_id',$idCurr)->first(); 
        $UserName = User::where('id',auth()->user()->id,)->pluck('name')->first(); 
        if($validated['list']=="Disclosure_of_all_sub_accounts_after_migration")
        {
          
                $customerMain ="";
                $AccountClassName="الحساب";
                if ($validated['accountlistradio'] === "subAccount") 
    {
        $SubAccounts = SubAccount::where('sub_account_id',$id)->get();
        $customerMain = SubAccount::where('sub_account_id', $id)->first();
        $balances = GeneralEntrie::selectRaw(
            'sub_accounts.sub_account_id,
            sub_accounts.sub_name,
            sub_accounts.Phone,
            SUM(CASE WHEN general_entries.entry_type = "debit" THEN general_entries.amount ELSE 0 END) as total_debit,
            SUM(CASE WHEN general_entries.entry_type = "credit" THEN general_entries.amount ELSE 0 END) as total_credit'
        )
        ->where('general_entries.accounting_period_id', $accountingPeriod->accounting_period_id) // تأكد من أن هذا العمود صحيح
        ->join('sub_accounts', function ($join) {
            $join->on('general_entries.sub_id', '=', 'sub_accounts.sub_account_id');
        })
        ->where('sub_accounts.sub_account_id', $customerMain->sub_account_id)

        ->groupBy('sub_accounts.sub_account_id', 'sub_accounts.sub_name', 'sub_accounts.Phone')
        ->get();
        $accountClasses = [
            1 => 'العميل',
            2 => 'المورد',
            3 => 'المخزن',
            4 => 'الحساب',
            5 => 'الصندوق',
        ];
        $AccountClassName = $accountClasses[$customerMain->AccountClass] ?? 'غير معروف';
        
 
    }
    if ($validated['accountlistradio'] === "mainAccount") 
    {
        $SubAccounts = SubAccount::where('Main_id',$id)->get();
        $customerMain = MainAccount::where('main_account_id', $id)->first();
        $balances = GeneralEntrie::selectRaw(
            'sub_accounts.sub_account_id,
            sub_accounts.sub_name,
            sub_accounts.Phone,
            SUM(CASE WHEN general_entries.entry_type = "debit" THEN general_entries.amount ELSE 0 END) as total_debit,
            SUM(CASE WHEN general_entries.entry_type = "credit" THEN general_entries.amount ELSE 0 END) as total_credit'
        )
        ->where('general_entries.accounting_period_id', $accountingPeriod->accounting_period_id) // تأكد من أن هذا العمود صحيح
        ->join('sub_accounts', function ($join) {
            $join->on('general_entries.sub_id', '=', 'sub_accounts.sub_account_id');
        })
        ->where('sub_accounts.Main_id', $customerMain->main_account_id)

        ->groupBy('sub_accounts.sub_account_id', 'sub_accounts.sub_name', 'sub_accounts.Phone')
        ->get();
        $accountClasses = [
            1 => 'العملاء',
            2 => 'الموردين',
             3=> 'المخازن',
            5=> 'الصناديق',
        ];
        $AccountClassName = $accountClasses[$customerMain->AccountClass] ?? 'غير معروف';
    }
        
              
                // dd(  $balances);
        
                $SumDebtor_amount = 0;
                $SumCredit_amount = 0;
            
                foreach ($SubAccounts as $balance) {
                    $customerMainAccount = SubAccount::where('sub_account_id', $balance->sub_account_id)->first();
            
                    $total_debit = GeneralEntrie::where('sub_id', $customerMainAccount->sub_account_id)
                        ->where('entry_type', 'debit')
                        ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
                        ->sum('amount');
            
                    $total_credit = GeneralEntrie::where('sub_id', $customerMainAccount->sub_account_id)
                        ->where('entry_type', 'credit')
                        ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
                        ->sum('amount');
                        $Sum_amount = ($total_debit ?? 0) - ($total_credit ?? 0);
                
                        if ($Sum_amount !== 0) {
                            if ($Sum_amount > 0) {
                                $SumDebtor_amount += $Sum_amount;
                                $SumDebtoramount = $Sum_amount;
                                $SumCreditamount = 0;
                            }
                            if ($Sum_amount < 0) {
                                $SumCredit_amount += $Sum_amount;
                                $SumCreditamount = $Sum_amount;
                                $SumDebtoramount = 0;
                            }
                        }
                }
        
        
        }
        $startDate = $accountingPeriod->created_at?->format('Y-m-d') ?? 'غير متوفر';
        $endDate = now()->toDateString();
// معالجة البيانات لإضافة الفارق ونوع
$Sale_priceSum = $SumDebtor_amount -abs( $SumCredit_amount);
$SumAmount = abs($SumDebtor_amount - $SumCredit_amount);
$numberToWords = new NumberToWords();
$numberTransformer = $numberToWords->getNumberTransformer('ar'); // اللغة العربية
$priceInWords=is_numeric($Sale_priceSum) 
? $numberTransformer->toWords($SumDebtor_amount -abs( $SumCredit_amount)) . ' ' . $currencysettings
: 'القيمة غير صالحة';
        $Myanalysis = " نهائي لكل الحسابات بعد ترحيل";
    
        return view('report.All-accounts-after-migration', [
            'balances' => $balances,
            'SumDebtor_amount' => $SumDebtor_amount,
            'SumCredit_amount' => $SumCredit_amount,
            'Myanalysis' => $Myanalysis,
            'currencysettings' => $currencysettings,
            'UserName' => $UserName,
            'accountingPeriod' => $accountingPeriod,
            'priceInWords' => $priceInWords,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'customerMain' => $customerMain,
            'AccountClassName' => $AccountClassName,
            'Sale_priceSum' => $Sale_priceSum,
        ])->render();
    }
    public function Full_disclosure_of_accounts_after_migration($validated, $id)
    {
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();

if($validated['list']=="Full_disclosure_of_accounts_after_migration")
{
    $balances = GeneralEntrie::selectRaw(
        'sub_accounts.sub_account_id,
        sub_accounts.sub_name,
        sub_accounts.Phone,

        SUM(CASE WHEN general_entries.entry_type = "debit" THEN general_entries.amount ELSE 0 END) as total_debit,
        SUM(CASE WHEN general_entries.entry_type = "credit" THEN general_entries.amount ELSE 0 END) as total_credit'
    )
    ->where('general_entries.accounting_period_id', $accountingPeriod->accounting_period_id) // تأكد من أن هذا العمود صحيح
    ->join('sub_accounts', function ($join) {
        $join->on('general_entries.sub_id', '=', 'sub_accounts.sub_account_id');
    })
    ->groupBy('sub_accounts.sub_account_id', 'sub_accounts.sub_name', 'sub_accounts.Phone')
    ->get();
    
 
$SubAccounts = SubAccount::all();

        $idCurr = 1;
    
        // تأكد من تعيين المتغير قبل استخدامه
        $curre = CurrencySetting::where('currency_settings_id', $idCurr)->first();
        $currencysettings = $curre->currency_name ?? 'ريال يمني';
        $UserName = User::where('id', auth()->user()->id)->pluck('name')->first();
    
        $SumDebtor_amount = 0;
        $SumCredit_amount = 0;
    
        foreach ($SubAccounts as $balance) {
            $customerMainAccount = SubAccount::where('sub_account_id', $balance->sub_account_id)->first();
    
            $total_debit = GeneralEntrie::where('sub_id', $customerMainAccount->sub_account_id)
                ->where('entry_type', 'debit')
                ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
                ->sum('amount');
    
            $total_credit = GeneralEntrie::where('sub_id', $customerMainAccount->sub_account_id)
                ->where('entry_type', 'credit')
                ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
                ->sum('amount');
    
            $Sum_amount = ($total_debit ?? 0) - ($total_credit ?? 0);
            
            if ($Sum_amount !== 0) {
                if ($Sum_amount > 0) {
                    $SumDebtor_amount += $Sum_amount;
                    $SumDebtoramount = $Sum_amount;
                    $SumCreditamount = 0;
                }
                if ($Sum_amount < 0) {
                    $SumCredit_amount += $Sum_amount;
                    $SumCreditamount = $Sum_amount;
                    $SumDebtoramount = 0;
                }
    
              
            }
        }
    }

    $customerMain='';
    $AccountClassName='';
        $startDate = $accountingPeriod->created_at?->format('Y-m-d') ?? 'غير متوفر';
        $endDate = now()->toDateString();
    
        $Sale_priceSum = abs($SumDebtor_amount) - abs($SumCredit_amount);
        $numberToWords = new NumberToWords();
        $numberTransformer = $numberToWords->getNumberTransformer('ar'); // اللغة العربية
        $priceInWords = is_numeric($Sale_priceSum)
            ? $numberTransformer->toWords($Sale_priceSum) . ' ' . $currencysettings
            : 'القيمة غير صالحة';

        $Myanalysis = " نهائي لكل الحسابات بعد ترحيل";
        return view('report.All-accounts-after-migration', [
            'balances' => $balances,
            'SumDebtor_amount' => $SumDebtor_amount,
            'SumCredit_amount' => $SumCredit_amount,
            'Myanalysis' => $Myanalysis,
            'currencysettings' => $currencysettings,
            'UserName' => $UserName,
            'accountingPeriod' => $accountingPeriod,
            'priceInWords' => $priceInWords,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'customerMain' => $customerMain,
            'AccountClassName' => $AccountClassName,
            'Sale_priceSum' => $Sale_priceSum,
        ])->render(); // إرجاع المحتوى كـ HTML
    } 
    private function FullDisclosureOfAccounts($validated, $id)
    {

        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
        $UserName = User::where('id',auth()->user()->id,)->pluck('name')->first(); 
        if($validated['list']=="FullDisclosureOfAccounts") 
        {
            $SubAccounts = SubAccount::all();
        $balances = DailyEntrie::selectRaw(
            'sub_accounts.sub_account_id,
            sub_accounts.sub_name,
            sub_accounts.Phone,
            SUM(CASE WHEN daily_entries.account_debit_id = sub_accounts.sub_account_id AND daily_entries.Currency_name = "ريال.يمني" THEN daily_entries.Amount_debit ELSE 0 END) as total_debit,
            SUM(CASE WHEN daily_entries.account_Credit_id = sub_accounts.sub_account_id AND daily_entries.Currency_name = "ريال.يمني" THEN daily_entries.Amount_Credit ELSE 0 END) as total_credit,
            SUM(CASE WHEN daily_entries.account_debit_id = sub_accounts.sub_account_id AND daily_entries.Currency_name = "ريال سعودي" THEN daily_entries.Amount_debit ELSE 0 END) as total_debits,
            SUM(CASE WHEN daily_entries.account_Credit_id = sub_accounts.sub_account_id AND daily_entries.Currency_name = "ريال سعودي" THEN daily_entries.Amount_Credit ELSE 0 END) as total_credits,
            SUM(CASE WHEN daily_entries.account_debit_id = sub_accounts.sub_account_id AND daily_entries.Currency_name = "دولار امريكي" THEN daily_entries.Amount_debit ELSE 0 END) as total_debitd,
            SUM(CASE WHEN daily_entries.account_Credit_id = sub_accounts.sub_account_id AND daily_entries.Currency_name = "دولار امريكي"  THEN daily_entries.Amount_Credit ELSE 0 END) as total_creditd
        ')
        ->join('sub_accounts', function ($join) {
            $join->on('daily_entries.account_debit_id', '=', 'sub_accounts.sub_account_id')
                 ->orOn('daily_entries.account_Credit_id', '=', 'sub_accounts.sub_account_id');
        })
        ->where('daily_entries.accounting_period_id', $accountingPeriod->accounting_period_id)
        ->groupBy('sub_accounts.sub_account_id', 'sub_accounts.sub_name', 'sub_accounts.Phone')
        ->get();
        $AccountClassName =  ' كل الحسابات ' ;
        $Myanalysis = "نهائي للحسابات الفرعية قبل الترحيل";     


    } 

    if($validated['list']=="FullDisclosureOfSubAccounts")
    {
        if ($validated['accountlistradio'] === "mainAccount")
        {
           $customerMainAccount = MainAccount::where('main_account_id', $id)->first();
           $idaccounn = $customerMainAccount->main_account_id;
           $balances = DailyEntrie::selectRaw(
            'sub_accounts.sub_account_id,
            sub_accounts.sub_name,
            sub_accounts.Phone,
   SUM(CASE WHEN daily_entries.account_debit_id = sub_accounts.sub_account_id AND daily_entries.Currency_name = "ريال.يمني" THEN daily_entries.Amount_debit ELSE 0 END) as total_debit,
            SUM(CASE WHEN daily_entries.account_Credit_id = sub_accounts.sub_account_id AND daily_entries.Currency_name = "ريال.يمني" THEN daily_entries.Amount_Credit ELSE 0 END) as total_credit,
            SUM(CASE WHEN daily_entries.account_debit_id = sub_accounts.sub_account_id AND daily_entries.Currency_name = "ريال سعودي" THEN daily_entries.Amount_debit ELSE 0 END) as total_debits,
            SUM(CASE WHEN daily_entries.account_Credit_id = sub_accounts.sub_account_id AND daily_entries.Currency_name = "ريال سعودي" THEN daily_entries.Amount_Credit ELSE 0 END) as total_credits,
            SUM(CASE WHEN daily_entries.account_debit_id = sub_accounts.sub_account_id AND daily_entries.Currency_name = "دولار امريكي" THEN daily_entries.Amount_debit ELSE 0 END) as total_debitd,
            SUM(CASE WHEN daily_entries.account_Credit_id = sub_accounts.sub_account_id AND daily_entries.Currency_name = "دولار امريكي"  THEN daily_entries.Amount_Credit ELSE 0 END) as total_creditd'
        )
        ->where('daily_entries.accounting_period_id', $accountingPeriod->accounting_period_id)
        ->join('sub_accounts', function ($join) {
            $join->on('daily_entries.account_debit_id', '=', 'sub_accounts.sub_account_id')
                 ->orOn('daily_entries.account_Credit_id', '=', 'sub_accounts.sub_account_id');
        })
        ->where('sub_accounts.Main_id', $idaccounn)
        ->groupBy('sub_accounts.sub_account_id', 'sub_accounts.sub_name', 'sub_accounts.Phone')
        ->get();
        $accountClasses = [
            1 => ' حساب العملاء :',
            2 => ' حساب الموردين:',
            3 => 'حساب الصناديق:',
            4 => 'الحساب:',
            5 => 'حساب المخازن:',
        ];
        $AccountClassName = $accountClasses[$customerMainAccount->AccountClass ??'']." ".$customerMainAccount->main_account_id." " .$customerMainAccount->Account_name ?? 'غير معروف' ;
        $Myanalysis = "نهائي للحساب الرئسي قبل الترحيل";     

     
        }
        if ($validated['accountlistradio'] === "subAccount")
         {
            $customer = SubAccount::where('sub_account_id', $id)->first();
            $idaccounn = $customer->sub_account_id;
           
            $balances = DailyEntrie::selectRaw(
                'sub_accounts.sub_account_id,
                sub_accounts.sub_name,
                sub_accounts.Phone,
               SUM(CASE WHEN daily_entries.account_debit_id = sub_accounts.sub_account_id AND daily_entries.Currency_name = "ريال.يمني" THEN daily_entries.Amount_debit ELSE 0 END) as total_debit,
            SUM(CASE WHEN daily_entries.account_Credit_id = sub_accounts.sub_account_id AND daily_entries.Currency_name = "ريال.يمني" THEN daily_entries.Amount_Credit ELSE 0 END) as total_credit,
            SUM(CASE WHEN daily_entries.account_debit_id = sub_accounts.sub_account_id AND daily_entries.Currency_name = "ريال سعودي" THEN daily_entries.Amount_debit ELSE 0 END) as total_debits,
            SUM(CASE WHEN daily_entries.account_Credit_id = sub_accounts.sub_account_id AND daily_entries.Currency_name = "ريال سعودي" THEN daily_entries.Amount_Credit ELSE 0 END) as total_credits,
            SUM(CASE WHEN daily_entries.account_debit_id = sub_accounts.sub_account_id AND daily_entries.Currency_name = "دولار امريكي" THEN daily_entries.Amount_debit ELSE 0 END) as total_debitd,
            SUM(CASE WHEN daily_entries.account_Credit_id = sub_accounts.sub_account_id AND daily_entries.Currency_name = "دولار امريكي"  THEN daily_entries.Amount_Credit ELSE 0 END) as total_creditd'
                
            )
            ->where('daily_entries.accounting_period_id', $accountingPeriod->accounting_period_id)
            ->join('sub_accounts', function ($join) {
                $join->on('daily_entries.account_debit_id', '=', 'sub_accounts.sub_account_id')
                     ->orOn('daily_entries.account_Credit_id', '=', 'sub_accounts.sub_account_id');
            })
            ->where('sub_accounts.sub_account_id',$customer->sub_account_id)
            ->groupBy('sub_accounts.sub_account_id', 'sub_accounts.sub_name', 'sub_accounts.Phone',   'daily_entries.Currency_name')
            ->get();
            $accountClasses = [
                1 => ' العميل:',
                2 => ' المورد:',
                3 => 'الحساب:',
                4 => 'الحساب:',
                5 => 'الحساب:',
            ];
            $AccountClassName = $accountClasses[$customer->AccountClass ??'']." ".$customer->sub_account_id." " .$customer->sub_name ?? 'غير معروف' ;
            $Myanalysis = "نهائي للحساب الفرعي قبل الترحيل";     

        } 

    
    
      
    } 
        $total_balance_YER =0;
        $total_balance_SAD = 0;
        $total_balance_USD = 0;
        $debit_YER   = 0;
        $credit_YER  = 0;
        $debits_SAD  = 0;
        $credits_SAD = 0;
        $debitd_USD  = 0;
        $credits_USD = 0;
        foreach($balances as $balance)
        {
            $debitd_USD+=$balance->total_debitd;
            $credits_USD+=$balance->total_creditd;
            $debit_YER+=$balance->total_debit;
            $credit_YER+=$balance->total_credit;
            $debits_SAD+=$balance->total_debits;
            $credits_SAD+=$balance->total_credits;
        }
            $SumDebtor_amount = 0;
            $SumCredit_amount = 0;
            $total_debits_SAD = 0;
            $total_credits_SAD = 0;     
            $YER="ريال.يمني";
            $SAD="ريال سعودي";
            $USD="دولار امريكي";
            $total_balance_YER=$debit_YER- $credit_YER;
            $total_balance_SAD=$debits_SAD- $credits_SAD;
            $total_balance_USD=$debitd_USD- $credits_USD;
        $startDate = $accountingPeriod->created_at->format('Y-m-d') ?? 'غير متوفر';
        $endDate = now()->toDateString();
       // معالجة البيانات لإضافة الفارق ونوع
        $Sale_priceSum = $SumDebtor_amount -abs( $SumCredit_amount);
        $SumAmount = abs($SumDebtor_amount - $SumCredit_amount);
       $numberToWords = new NumberToWords();
        $numberTransformer = $numberToWords->getNumberTransformer('ar'); // اللغة العربية
        $priceInWordsYER=is_numeric($total_balance_YER) 
        ? $numberTransformer->toWords(abs( $total_balance_YER)) .' '.$YER
        : 'القيمة غير صالحة';
        $numberTransformer = $numberToWords->getNumberTransformer('ar'); // اللغة العربية
        $priceInWordsSAD=is_numeric($total_balance_SAD) 
        ? $numberTransformer->toWords(abs($total_balance_SAD)) . ' ' . $SAD
        : 'القيمة غير صالحة';
        $numberTransformer = $numberToWords->getNumberTransformer('ar'); // اللغة العربية
        $priceInWordsUSD=is_numeric($total_balance_USD) 
        ? $numberTransformer->toWords(abs($total_balance_USD)) . ' ' . $USD
        : 'القيمة غير صالحة';
 

return view('report.Final-full-disclosure', compact(
    'Myanalysis',
    'Myanalysis',
    'balances',
    'AccountClassName',
    'UserName',
    'accountingPeriod',
    'priceInWordsYER',
    'priceInWordsUSD',
    'priceInWordsSAD',
    'startDate',
    'endDate',
    'debit_YER' ,  
'credit_YER',
'debits_SAD' , 
'credits_SAD' ,
'debitd_USD' , 
'credits_USD' ,
'total_balance_YER',
'total_balance_SAD',
'total_balance_USD',
 'YER',
 'SAD',
 'USD',
));

}
    
    private function FullDisclosureOfSubAccounts($validated,$id)
    {
        // dd(2);

        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
        $idCurr=1;
        $currencysettings=$curre->currency_name ?? 'ريال يمني';
        $curre=CurrencySetting::where('currency_settings_id',$idCurr)->first(); 
        $UserName = User::where('id',auth()->user()->id,)->pluck('name')->first(); 
        if($validated['list']=="FullDisclosureOfSubAccounts")
        {
            if ($validated['accountlistradio'] === "mainAccount")
            {
               $customerMainAccount = MainAccount::where('main_account_id', $id)->first();
               $SubAccounts = SubAccount::where('Main_id',$customerMainAccount->main_account_id)->get();
               $idaccounn = $customerMainAccount->main_account_id;
               $balances = DailyEntrie::selectRaw(
                'sub_accounts.sub_account_id,
                sub_accounts.sub_name,
                sub_accounts.Phone,
                SUM(CASE WHEN daily_entries.account_debit_id = sub_accounts.sub_account_id THEN daily_entries.Amount_debit ELSE 0 END) as total_debit,
                SUM(CASE WHEN daily_entries.account_Credit_id = sub_accounts.sub_account_id THEN daily_entries.Amount_Credit ELSE 0 END) as total_credit'
            )
            ->where('daily_entries.accounting_period_id', $accountingPeriod->accounting_period_id)
            ->join('sub_accounts', function ($join) {
                $join->on('daily_entries.account_debit_id', '=', 'sub_accounts.sub_account_id')
                     ->orOn('daily_entries.account_Credit_id', '=', 'sub_accounts.sub_account_id');
            })
            ->where('sub_accounts.Main_id', $idaccounn)
            ->groupBy('sub_accounts.sub_account_id', 'sub_accounts.sub_name', 'sub_accounts.Phone')
            ->get();
           

         
            }
            if ($validated['accountlistradio'] === "subAccount")
             {
                $SubAccounts = SubAccount::where('sub_account_id',$id)->get();
                $customer = SubAccount::where('sub_account_id', $id)->first();

                $idaccounn = $customer->sub_account_id;
                $balances = DailyEntrie::selectRaw(
                    'sub_accounts.sub_account_id,
                    sub_accounts.sub_name,
                    sub_accounts.Phone,
                    daily_entries.Currency_name,
                    
                    SUM(CASE WHEN daily_entries.account_debit_id = sub_accounts.sub_account_id AND daily_entries.Currency_name = \'ريال.يمني\' THEN daily_entries.Amount_debit ELSE 0 END) as total_debits,
                    SUM(CASE WHEN daily_entries.account_Credit_id = sub_accounts.sub_account_id AND daily_entries.Currency_name = \'ريال.يمني\' THEN daily_entries.Amount_Credit ELSE 0 END) as total_credits,

                    SUM(CASE WHEN daily_entries.account_debit_id = sub_accounts.sub_account_id THEN daily_entries.Amount_debit ELSE 0 END) as total_debit,
                    SUM(CASE WHEN daily_entries.account_Credit_id = sub_accounts.sub_account_id THEN daily_entries.Amount_Credit ELSE 0 END) as total_credit'
                    
                )
                ->where('daily_entries.accounting_period_id', $accountingPeriod->accounting_period_id)
                ->join('sub_accounts', function ($join) {
                    $join->on('daily_entries.account_debit_id', '=', 'sub_accounts.sub_account_id')
                         ->orOn('daily_entries.account_Credit_id', '=', 'sub_accounts.sub_account_id');
                })
                ->where('sub_accounts.sub_account_id',$customer->sub_account_id)
                ->groupBy('sub_accounts.sub_account_id', 'sub_accounts.sub_name', 'sub_accounts.Phone',   'daily_entries.Currency_name')
                ->get();
               
    
            } 

            $SumDebtor_amount = 0;
            $SumCredit_amount = 0;
            $amountCredit = 0;
            $amountDebit = 0;
        
            foreach ($SubAccounts as $balance) {
                $customerMainAccount = SubAccount::where('sub_account_id', $balance->sub_account_id)->first();
        
                    $total_debit=DailyEntrie::where('accounting_period_id', $accountingPeriod->accounting_period_id)
                    ->where('account_debit_id',$balance->sub_account_id)
                    ->sum('Amount_debit');
                    $total_credit=DailyEntrie::where('accounting_period_id', $accountingPeriod->accounting_period_id)
                    ->where('account_Credit_id',$balance->sub_account_id)
                    ->sum('Amount_Credit');
                
        
                $Sum_amount = ($total_debit ?? 0) - ($total_credit ?? 0);
                
                if ($Sum_amount !== 0) {
                    if ($Sum_amount > 0) {
                        $SumDebtor_amount += $Sum_amount;
                    }
                    if ($Sum_amount < 0) {
                        $SumCredit_amount += $Sum_amount;
                    }
                }
          
            }
        } 
                $startDate = $accountingPeriod->created_at?->format('Y-m-d') ?? 'غير متوفر';
                $endDate = now()->toDateString();
       // معالجة البيانات لإضافة الفارق ونوع
        $Sale_priceSum = $SumDebtor_amount -abs( $SumCredit_amount);
        $SumAmount = abs($SumDebtor_amount - $SumCredit_amount);
       $numberToWords = new NumberToWords();
        $numberTransformer = $numberToWords->getNumberTransformer('ar'); // اللغة العربية
        $priceInWords=is_numeric($Sale_priceSum) 
        ? $numberTransformer->toWords($SumDebtor_amount -abs( $SumCredit_amount)) . ' ' . $currencysettings
        : 'القيمة غير صالحة';
   $accountClasses = [
    1 => 'الحساب',
    2 => 'الحساب',
    3 => 'الحساب',
    4 => 'الحساب',
    5 => 'الحساب',
];
$AccountClassName = $accountClasses[$customerMainAccount->AccountClass ??''] ?? 'غير معروف';
$Myanalysis = "نهائي للحسابات الفرعية قبل الترحيل";
$balances = DailyEntrie::selectRaw(
    'sub_accounts.sub_account_id,
    sub_accounts.sub_name,
    sub_accounts.Phone,
    MAX(CASE WHEN daily_entries.Currency_name = "ريال.يمني" THEN 1 ELSE 0 END) as has_yer,
    MAX(CASE WHEN daily_entries.Currency_name = "ريال سعودي" THEN 1 ELSE 0 END) as has_sar,
    MAX(CASE WHEN daily_entries.Currency_name = "دولار" THEN 1 ELSE 0 END) as has_usd,
    SUM(CASE WHEN daily_entries.account_debit_id = sub_accounts.sub_account_id AND daily_entries.Currency_name = "ريال.يمني" THEN daily_entries.Amount_debit ELSE 0 END) as total_debit,
    SUM(CASE WHEN daily_entries.account_Credit_id = sub_accounts.sub_account_id AND daily_entries.Currency_name = "ريال.يمني" THEN daily_entries.Amount_Credit ELSE 0 END) as total_credit,
    SUM(CASE WHEN daily_entries.account_debit_id = sub_accounts.sub_account_id AND daily_entries.Currency_name = "ريال سعودي" THEN daily_entries.Amount_debit ELSE 0 END) as total_debits,
    SUM(CASE WHEN daily_entries.account_Credit_id = sub_accounts.sub_account_id AND daily_entries.Currency_name = "ريال سعودي" THEN daily_entries.Amount_Credit ELSE 0 END) as total_credits,
    SUM(CASE WHEN daily_entries.account_debit_id = sub_accounts.sub_account_id AND daily_entries.Currency_name = "دولار" THEN daily_entries.Amount_debit ELSE 0 END) as total_debitd,
    SUM(CASE WHEN daily_entries.account_Credit_id = sub_accounts.sub_account_id AND daily_entries.Currency_name = "دولار" THEN daily_entries.Amount_Credit ELSE 0 END) as total_creditd
')
->join('sub_accounts', function ($join) {
    $join->on('daily_entries.account_debit_id', '=', 'sub_accounts.sub_account_id')
         ->orOn('daily_entries.account_Credit_id', '=', 'sub_accounts.sub_account_id');
})
->where('daily_entries.accounting_period_id', $accountingPeriod->accounting_period_id)
->groupBy('sub_accounts.sub_account_id', 'sub_accounts.sub_name', 'sub_accounts.Phone')
->get();
return view('report.Final-full-disclosure', compact(
    'Myanalysis', 'SumAmount', 'balances', 
    'AccountClassName', 'currencysettings', 'UserName', 
    'amountCredit',
    'SumDebtor_amount',
    'accountingPeriod',
    'SumCredit_amount',
    'amountDebit',
    'priceInWords',
    'startDate',
    'endDate',
    'customerMainAccount', 
    'Sale_priceSum'
));
    }

    public function showStatementMainAccountTotally( $validated, $id)
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
            );

            $startDate = null;
            $endDate = null;
            
            // تخصيص الفترة الزمنية بناءً على المدخلات
            switch ($validated['listradio'] ?? '') {
                case '1': // 
                    $query->where('daily_entries.accounting_period_id',$accountingPeriod->accounting_period_id);
                    break;
                case '2': // اليوم
                    $query->where('daily_entries.accounting_period_id',$accountingPeriod->accounting_period_id);

                    $startDate = now()->toDateString();
                    $endDate = now()->toDateString();
                    $query->whereDate('daily_entries.created_at', $startDate);
                    break;
                case '3': // هذا الأسبوع
                    $query->where('daily_entries.accounting_period_id',$accountingPeriod->accounting_period_id);

                    $startDate = now()->startOfWeek()->toDateString();
                    $endDate = now()->endOfWeek()->toDateString();
                    $query->whereBetween('daily_entries.created_at', [$startDate, $endDate]);
                    break;
                case '4': // هذا الشهر
                    $query->where('daily_entries.accounting_period_id',$accountingPeriod->accounting_period_id);
                    $startDate = now()->startOfMonth()->toDateString();
                    $endDate = now()->endOfMonth()->toDateString();
                    $query->whereMonth('daily_entries.created_at', now()->month)
                          ->whereYear('daily_entries.created_at', now()->year);
                    break;
                case '5': // هذا الشهر
                    if ($validated['fromDate'] && $validated['toDate']) 
                {
                    $query->whereBetween('daily_entries.created_at',[$validated['fromDate'],$validated['toDate']]);
                    $startDate=$validated['fromDate'];
                    $endDate=$validated['toDate'];
                      break;

                }
                else
                {
                    $query->orderBy('daily_entries.created_at', 'asc'); // تصحيح ترتيب النتائج
                    $startDate = $accountingPeriod->created_at?->format('Y-m-d') ?? 'غير متوفر';
                    $endDate = now()->toDateString();
                    break;
                }
          
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
        $startDate = null;
$endDate = null;

      // تخصيص الفترة الزمنية بناءً على المدخلات
      switch ($validated['listradio'] ?? '') {
        case '1': // 
            $query->where('daily_entries.accounting_period_id',$accountingPeriod->accounting_period_id);
            break;
        case '2': // اليوم
            $query->where('daily_entries.accounting_period_id',$accountingPeriod->accounting_period_id);

            $startDate = now()->toDateString();
            $endDate = now()->toDateString();
            $query->whereDate('daily_entries.created_at', $startDate);
            break;
        case '3': // هذا الأسبوع
            $query->where('daily_entries.accounting_period_id',$accountingPeriod->accounting_period_id);

            $startDate = now()->startOfWeek()->toDateString();
            $endDate = now()->endOfWeek()->toDateString();
            $query->whereBetween('daily_entries.created_at', [$startDate, $endDate]);
            break;
        case '4': // هذا الشهر
            $query->where('daily_entries.accounting_period_id',$accountingPeriod->accounting_period_id);
            $startDate = now()->startOfMonth()->toDateString();
            $endDate = now()->endOfMonth()->toDateString();
            $query->whereMonth('daily_entries.created_at', now()->month)
                  ->whereYear('daily_entries.created_at', now()->year);
            break;
            case '5': // هذا الشهر
                if ($validated['fromDate'] && $validated['toDate']) 
            {
                $query->whereBetween('daily_entries.created_at',[$validated['fromDate'],$validated['toDate']]);
                $startDate=$validated['fromDate'];
                $endDate=$validated['toDate'];
                  break;

            }
            else
            {
                $query->orderBy('daily_entries.created_at', 'asc'); // تصحيح ترتيب النتائج
                $startDate = $accountingPeriod->created_at?->format('Y-m-d') ?? 'غير متوفر';
                $endDate = now()->toDateString();
                break;
            }
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
                    // dd( $Myanalysis);

    $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
    $customer = SubAccount::where('sub_account_id',$id)->first(); // استرجاع بيانات العميل
    $idCurr=1;
    $curre=CurrencySetting::where('currency_settings_id',$idCurr)->first(); 
    $UserName = User::where('id',auth()->user()->id,)->pluck('name')->first();    
    $currencysettings=$curre->currency_name ?? 'ريال يمني';
    // $pb = DailyEntrie::all();
    // foreach ($pb as $p) {
    //     if ($p->Currency_name!="ريال سعودي") {
    //         $p->Currency_name = "ريال.يمني";
    //         $p->save();
    //     }
    // }
 
    $query = DailyEntrie::with(['debitAccount', 'debitAccount.mainAccount', 'creditAccount', 'creditAccount.mainAccount'])
    ->selectRaw(
        'daily_entries.account_debit_id,
         daily_entries.daily_entries_type,
         daily_entries.account_Credit_id,
         daily_entries.Invoice_type,
         daily_entries.Invoice_id,
         daily_entries.Statement,
         daily_entries.entrie_id,
         daily_entries.Currency_name,
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
        'daily_entries.Currency_name',

        'daily_entries.Invoice_id',
        'daily_entries.created_at'
    );
    // $query->whereIn('daily_entries.Currency_name', ['ريال سعودي','ريال.يمني']);

    $startDate = null;
    $endDate = null;
          // تخصيص الفترة الزمنية بناءً على المدخلات
          switch ($validated['listradio'] ?? '') {
            case '1': // 
                $query->where('daily_entries.accounting_period_id',$accountingPeriod->accounting_period_id);
                $startDate = $accountingPeriod->created_at?->format('Y-m-d');
                $endDate = now()->toDateString();
                break;
            case '2': // اليوم
                $query->where('daily_entries.accounting_period_id',$accountingPeriod->accounting_period_id);

                $startDate = now()->toDateString();
                $endDate = now()->toDateString();
                $query->whereDate('daily_entries.created_at', $startDate);
                break;
            case '3': // هذا الأسبوع
                $query->where('daily_entries.accounting_period_id',$accountingPeriod->accounting_period_id);

                $startDate = now()->startOfWeek()->toDateString();
                $endDate = now()->endOfWeek()->toDateString();
                $query->whereBetween('daily_entries.created_at', [$startDate, $endDate]);
                break;
            case '4': // هذا الشهر
                $query->where('daily_entries.accounting_period_id',$accountingPeriod->accounting_period_id);
                $startDate = now()->startOfMonth()->toDateString();
                $endDate = now()->endOfMonth()->toDateString();
                $query->whereMonth('daily_entries.created_at', now()->month)
                      ->whereYear('daily_entries.created_at', now()->year);
                break;
            case '5': // هذا الشهر
                if ($validated['fromDate'] && $validated['toDate']) 
            {
                $query->whereBetween('daily_entries.created_at',[$validated['fromDate'],$validated['toDate']]);
                $startDate=$validated['fromDate'];
                $endDate=$validated['toDate'];
                  break;

            }
            else
            {
                $query->orderBy('daily_entries.created_at', 'asc'); // تصحيح ترتيب النتائج
                $startDate = $accountingPeriod->created_at?->format('Y-m-d') ?? 'غير متوفر';
                $endDate = now()->toDateString();
                break;
            }
      
        
        }
                                // جلب القيود اليومية مع الإجماليات
                                $entries = $query->get();
                                // حساب الإجماليات (بناءً على البيانات المسترجعة)
                                $SumDebtor_amount = $entries->where('Currency_name', 'ريال.يمني')->sum('total_debit');
                                $SumCredit_amount = $entries->where('Currency_name', 'ريال.يمني')->sum('total_credit');
                                $amount_YER=$SumDebtor_amount - $SumCredit_amount??0;
                                $currencysettings='ريال.يمني';
                                $SumDebtor_amountASR = $entries->where('Currency_name', 'ريال سعودي')->sum('total_debit');
                                $SumCredit_amountASR = $entries->where('Currency_name', 'ريال سعودي')->sum('total_credit'); 
                                $amountASR=$SumDebtor_amountASR - $SumCredit_amountASR??0;
                                $currencysettingsASR="ريال سعودي";
                                $SumDebtor_amountUSD = $entries->where('Currency_name', 'دولار امريكي')->sum('total_debit');
                                $SumCredit_amountUSD = $entries->where('Currency_name', 'دولار امريكي')->sum('total_credit');
                                $amountUSD=$SumDebtor_amountUSD - $SumCredit_amountUSD??0;
                                $currencysettingsUSD='دولار امريكي';


                                // dd( $SumDebtor_amounts , $SumCredit_amounts);  
                            
                            $AccountClassName = $accountClasses[$customer->AccountClass] ?? 'غير معروف';
                                $Sale_priceSum = abs($SumDebtor_amount - $SumCredit_amount);
                               $numberToWords = new NumberToWords();
                                $numberTransformer = $numberToWords->getNumberTransformer('ar'); // اللغة العربية
                                $priceInWords=is_numeric($Sale_priceSum) 
                                ? $numberTransformer->toWords( abs($SumDebtor_amount - $SumCredit_amount)) . ' ' . $currencysettings
                                : 'القيمة غير صالحة';

                                $numberToWordsASR = new NumberToWords();
                                $numberTransformerASR = $numberToWords->getNumberTransformer('ar'); // اللغة العربية
                                $priceInWordsASR=is_numeric($amountASR) ? $numberTransformerASR->toWords( abs($amountASR)) . ' ' . $currencysettingsASR
                                : 'القيمة غير صالحة';

                               $numberToWordsUSD = new NumberToWords();
                                $numberTransformerUSD = $numberToWords->getNumberTransformer('ar'); // اللغة العربية
                                $priceInWordsUSD=is_numeric($amountUSD) ? $numberTransformerUSD->toWords( abs($amountUSD)) . ' ' . $currencysettingsUSD
                                : 'القيمة غير صالحة';
                                

                           $accountClasses = [
                            1 => 'العميل',
                            2 => 'المورد',
                            3 => 'المخزن',
                            4 => 'الحساب',
                            5 => 'الصندوق',
                        ];

                        $AccountClassName = $accountClasses[$customer->AccountClass] ?? 'غير معروف';
                           return view('customers.statement', compact('customer','startDate', 'endDate','Myanalysis', 
                           'entries',
                           'endDate',
                           'startDate',

                           'AccountClassName',
                           'currencysettings',
                           'currencysettingsASR',
                           'currencysettingsUSD',
                           'UserName',
                           'accountingPeriod',
                           'SumCredit_amount',
                           'SumDebtor_amount',
                           'SumDebtor_amountASR',
                           'SumCredit_amountASR',
                           'SumDebtor_amountUSD',
                           'SumCredit_amountUSD',
                           'priceInWords',
                           'priceInWordsASR',
                           'priceInWordsUSD',
                           'Sale_priceSum',
                           'amountASR',
                           'amount_YER',
                           'amountUSD'


                           ))->render(); // إرجاع المحتوى كـ HTML
                        }
                    
                        public function getDailyEntriesMainAccountMyanalysis($validated, $id)
                        {
                            $Myanalysis="تحليلي";
                            // dd($entries);
                            $UserName = User::where('id',auth()->user()->id,)->pluck('name')->first();    

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
         daily_entries.Currency_name,
         daily_entries.created_at,
  
         SUM(CASE WHEN daily_entries.account_debit_id = sub_accounts.sub_account_id THEN daily_entries.Amount_debit ELSE 0 END) as total_debit,
         SUM(CASE WHEN daily_entries.account_Credit_id = sub_accounts.sub_account_id THEN daily_entries.Amount_Credit ELSE 0 END) as total_credit'
    )
    ->join('sub_accounts', function ($join) {
        $join->on('daily_entries.account_debit_id', '=', 'sub_accounts.sub_account_id')
             ->orOn('daily_entries.account_Credit_id', '=', 'sub_accounts.sub_account_id');
    }) ->join('main_accounts', 'sub_accounts.Main_id', '=', 'main_accounts.main_account_id')
    ->where('main_accounts.main_account_id', $id)
    ->groupBy(
    
        'daily_entries.account_debit_id',
        'daily_entries.Invoice_type',
        'daily_entries.account_Credit_id',
        'daily_entries.daily_entries_type',
        'daily_entries.Statement',
        'daily_entries.entrie_id',
        'daily_entries.Currency_name',

        'daily_entries.Invoice_id',
        'daily_entries.created_at'
    );
                                $startDate = null;
                                $endDate = null;
                                      // تخصيص الفترة الزمنية بناءً على المدخلات
                                      switch ($validated['listradio'] ?? '') {
                                        case '1': // 
                                            $query->where('daily_entries.accounting_period_id',$accountingPeriod->accounting_period_id);
                                            break;
                                        case '2': // اليوم
                                            $query->where('daily_entries.accounting_period_id',$accountingPeriod->accounting_period_id);
                                
                                            $startDate = now()->toDateString();
                                            $endDate = now()->toDateString();
                                            $query->whereDate('daily_entries.created_at', $startDate);
                                            break;
                                        case '3': // هذا الأسبوع
                                            $query->where('daily_entries.accounting_period_id',$accountingPeriod->accounting_period_id);
                                
                                            $startDate = now()->startOfWeek()->toDateString();
                                            $endDate = now()->endOfWeek()->toDateString();
                                            $query->whereBetween('daily_entries.created_at', [$startDate, $endDate]);
                                            break;
                                        case '4': // هذا الشهر
                                            $query->where('daily_entries.accounting_period_id',$accountingPeriod->accounting_period_id);
                                            $startDate = now()->startOfMonth()->toDateString();
                                            $endDate = now()->endOfMonth()->toDateString();
                                            $query->whereMonth('daily_entries.created_at', now()->month)
                                                  ->whereYear('daily_entries.created_at', now()->year);
                                            break;
                                            case '5': // هذا الشهر
                                                if ($validated['fromDate'] && $validated['toDate']) 
                                            {
                                                $query->whereBetween('daily_entries.created_at',[$validated['fromDate'],$validated['toDate']]);
                                                $startDate=$validated['fromDate'];
                                                $endDate=$validated['toDate'];
                                                  break;
                                
                                            }
                                            else
                                            {
                                                $query->orderBy('daily_entries.created_at', 'asc'); // تصحيح ترتيب النتائج
                                                $startDate = $accountingPeriod->created_at?->format('Y-m-d') ?? 'غير متوفر';
                                                $endDate = now()->toDateString();
                                                break;
                                            }
                                    }
                                
                                    $entries = $query->get();
                                    // حساب الإجماليات (بناءً على البيانات المسترجعة)
                                    $SumDebtor_amount = $entries->where('Currency_name', 'ريال.يمني')->sum('total_debit');
                                    $SumCredit_amount = $entries->where('Currency_name', 'ريال.يمني')->sum('total_credit');
                                    $amount_YER=$SumDebtor_amount - $SumCredit_amount??0;
                                    $currencysettings='ريال.يمني';
                                    $SumDebtor_amountASR = $entries->where('Currency_name', 'ريال سعودي')->sum('total_debit');
                                    $SumCredit_amountASR = $entries->where('Currency_name', 'ريال سعودي')->sum('total_credit'); 
                                    $amountASR=$SumDebtor_amountASR - $SumCredit_amountASR??0;
                                    $currencysettingsASR="ريال سعودي";
                                    $SumDebtor_amountUSD = $entries->where('Currency_name', 'دولار امريكي')->sum('total_debit');
                                    $SumCredit_amountUSD = $entries->where('Currency_name', 'دولار امريكي')->sum('total_credit');
                                    $amountUSD=$SumDebtor_amountUSD - $SumCredit_amountUSD??0;
                                    $currencysettingsUSD='دولار امريكي';
    
    
                                    // dd( $SumDebtor_amounts , $SumCredit_amounts);  
                                
                                $AccountClassName = $accountClasses[$customer->AccountClass] ?? 'غير معروف';
                                    $Sale_priceSum = abs($SumDebtor_amount - $SumCredit_amount);
                                   $numberToWords = new NumberToWords();
                                    $numberTransformer = $numberToWords->getNumberTransformer('ar'); // اللغة العربية
                                    $priceInWords=is_numeric($Sale_priceSum) 
                                    ? $numberTransformer->toWords( abs($SumDebtor_amount - $SumCredit_amount)) . ' ' . $currencysettings
                                    : 'القيمة غير صالحة';
    
                                    $numberToWordsASR = new NumberToWords();
                                    $numberTransformerASR = $numberToWords->getNumberTransformer('ar'); // اللغة العربية
                                    $priceInWordsASR=is_numeric($amountASR) ? $numberTransformerASR->toWords( abs($amountASR)) . ' ' . $currencysettingsASR
                                    : 'القيمة غير صالحة';
    
                                   $numberToWordsUSD = new NumberToWords();
                                    $numberTransformerUSD = $numberToWords->getNumberTransformer('ar'); // اللغة العربية
                                    $priceInWordsUSD=is_numeric($amountUSD) ? $numberTransformerUSD->toWords( abs($amountUSD)) . ' ' . $currencysettingsUSD
                                    : 'القيمة غير صالحة';
                                    
    
                               $accountClasses = [
                                1 => 'العميل',
                                2 => 'المورد',
                                3 => 'المخزن',
                                4 => 'الحساب',
                                5 => 'الصندوق',
                            ];
    
                            $AccountClassName = "الحساب الرئيسي";
                            return view('customers.statement', compact('customer','startDate', 'endDate','Myanalysis', 
                               'entries',
                               'endDate',
                               'startDate',
    
                               'AccountClassName',
                               'currencysettings',
                               'currencysettingsASR',
                               'currencysettingsUSD',
                               'UserName',
                               'accountingPeriod',
                               'SumCredit_amount',
                               'SumDebtor_amount',
                               'SumDebtor_amountASR',
                               'SumCredit_amountASR',
                               'SumDebtor_amountUSD',
                               'SumCredit_amountUSD',
                               'priceInWords',
                               'priceInWordsASR',
                               'priceInWordsUSD',
                               'Sale_priceSum',
                               'amountASR',
                               'amount_YER',
                               'amountUSD'
    
    
                               ))->render();
                        
                          
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
