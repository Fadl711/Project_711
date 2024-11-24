<?php

namespace App\Http\Controllers;

use App\Enum\AccountClass;
use App\Models\AccountingPeriod;
use App\Models\DailyEntrie;
use App\Models\GeneralJournal;
use App\Models\MainAccount;
use App\Models\SubAccount;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CustomerCoctroller extends Controller
{

    public function index(){

        return view('customers.index');
    }
    public function show(){

        return view('customers.show');
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

        $debtor_amount = $request->input('debtor_amount', '٠١٢٣٤٥٦٧٨٩');
        $creditor_amount = $request->input('creditor_amount', '٠١٢٣٤٥٦٧٨٩');
        $Phone1 = $this->convertArabicToEnglish($request->input('Phone', '٠١٢٣٤٥٦٧٨٩'));
        $sub_name = $request->sub_name;

        $mainAccount=MainAccount::where('AccountClass',AccountClass::CUSTOMER->value)->first();
        $User_id=auth()->user()->id;
        if (!$mainAccount) {
            return response()->json(['success' => false, 'message' => 'فشل في إنشاء صفحة يومية']);
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
            // if ($DSubAccount->debtor_amount) {
            //     return response()->json(['success' => false, 'message' =>$DSubAccount->debtor_amount]);
            // }
          
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
        
                if (!$dailyPage || !$dailyPage->page_id) {
                    return response()->json(['success' => false, 'message' => 'فشل في إنشاء صفحة يومية']);
                }
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
                    'Invoice_type' => 'رصيد افتتاحي',

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
