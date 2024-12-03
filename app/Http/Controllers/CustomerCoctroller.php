<?php

namespace App\Http\Controllers;

use App\Enum\AccountClass;
use App\Models\AccountingPeriod;
use App\Models\Currency;
use App\Models\CurrencySetting;
use App\Models\DailyEntrie;
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
 $balances = DailyEntrie::selectRaw(
    'sub_accounts.sub_account_id,
     sub_accounts.sub_name,
     sub_accounts.Phone,
     SUM(CASE WHEN daily_entries.account_debit_id = sub_accounts.sub_account_id THEN daily_entries.Amount_debit ELSE 0 END) as total_debit,
     SUM(CASE WHEN daily_entries.account_Credit_id = sub_accounts.sub_account_id THEN daily_entries.Amount_Credit ELSE 0 END) as total_credit'
)
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
        // return view('customers.show');
    }
    public function showStatement(Request $request, $id)
{

    $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
    $customer = SubAccount::where('sub_account_id',$id)->first(); // استرجاع بيانات العميل
    $idCurr=1;
    $curre=CurrencySetting::where('currency_settings_id',$idCurr)->first(); 
    $UserName = User::where('id',auth()->user()->id,)->pluck('name')->first();
    
    // $curre2=Currency::where('currency_id',$curre->Currency_id)->first();
    
    $currencysettings=$curre->currency_name ?? 'ريال يمني';

    $SumDebtor_amount=DailyEntrie::where('account_debit_id',$customer->sub_account_id)->sum('Amount_debit');
    $SumCredit_amount=DailyEntrie::where('account_Credit_id',$customer->sub_account_id)->sum('Amount_Credit');
    $Sale_priceSum = abs($SumDebtor_amount - $SumCredit_amount);

   $numberToWords = new NumberToWords();
    $numberTransformer = $numberToWords->getNumberTransformer('ar'); // اللغة العربية
    $priceInWords=is_numeric($Sale_priceSum) 
    ? $numberTransformer->toWords( abs($SumDebtor_amount - $SumCredit_amount)) . ' ' . $currencysettings
    : 'القيمة غير صالحة';
    $entries = DailyEntrie::where('account_debit_id', $id)
                           ->orWhere('account_Credit_id', $id)
                           ->get(); // استرجاع القيود المرتبطة بالعميل
                           $accountClasses = [
                            1 => 'العميل',
                            2 => 'المورد',
                            3 => 'المخزن',
                            4 => 'الحساب',
                            5 => 'الصندوق',
                        ];
                        $AccountClassName = $accountClasses[$customer->AccountClass] ?? 'غير معروف';
    
                           return view('customers.statement', compact('customer', 'entries','AccountClassName','currencysettings','UserName','accountingPeriod','SumCredit_amount','SumDebtor_amount','priceInWords','Sale_priceSum'))->render(); // إرجاع المحتوى كـ HTML
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
