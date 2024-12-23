<?php

namespace App\Http\Controllers\Accounts\main_accounts;

use App\Enum\AccountClass;
use App\Enum\AccountType;
use App\Enum\Deportatton;
use App\Enum\IntOrderStatus;
use App\Http\Controllers\Controller;
use App\Models\AccountingPeriod;
use App\Models\DailyEntrie;
use App\Models\GeneralJournal;
use App\Models\MainAccount;
use App\Models\SubAccount;
use Carbon\Carbon;
use Illuminate\Support\Facades\Db;

// use Illuminate\Container\Attributes\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use function Laravel\Prompts\select;
use function PHPUnit\Framework\isNull;

class MainaccountController extends Controller

{
    
    public function create(){
        $mainAccount=MainAccount::all();//
        $subAccount = SubAccount::all();
 
 $classd=AccountClass::cases();
 $cus = MainAccount::where('AccountClass', $classd[0])->get();//+


return view('accounts.Main_Account.create',
[ 'mainAccounts'=>$mainAccount,'subAccounts'=>$subAccount,'cuo'=> $cus]);  }

    public function convertArabicToEnglish($number)
    {
        // استبدال الأرقام العربية بما يعادلها من الإنجليزية
        $arabicNumbers = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        return str_replace($arabicNumbers, $englishNumbers, $number);
    }
    public function store(Request $request)
    {
        // التحقق من صلاحية المستخدم
        $User_id = $request->User_id;

        if (is_null($User_id)) {
            return response()->json(['message' => 'لا يوجد للمستخدم أي صلاحية للإضافة حساب'], 403);
        }
        $account_name = $request->account_name;
        $typeAccount = $request->typeAccount;
        $Nature_account = $request->Nature_account;
        $Type_migration = $request->Type_migration;
    
        // جلب المدخلات وتحويل الأرقام العربية إلى الإنجليزية
        $debtor_amount1 = $request->input('debtor_amount', '٠١٢٣٤٥٦٧٨٩');
        $creditor_amount1 = $request->input('creditor_amount', '٠١٢٣٤٥٦٧٨٩');
        $Phone1 = $request->input('Phone', '٠١٢٣٤٥٦٧٨٩');
        $Known_phone=$request->Known_phone;
                $name_The_known = $request->name_The_known;
        // تحويل الأرقام العربية إلى الإنجليزية
        $creditor_amount = $this->convertArabicToEnglish($creditor_amount1);
        $debtor_amount = $this->convertArabicToEnglish($debtor_amount1);
        // التحقق مما إذا كان الحساب موجودًا بالفعل
        $account_nametExists = MainAccount::where('account_name', $account_name)->exists();
        if ($account_nametExists) {
            return response()->json(['success' => false,'message' => 'هذا الاسم موجود من قبل']);
        }
        // __________________________MainAccount______________________________________
        $mainAccount = MainAccount::create([
            'account_name' => $account_name,
            'Nature_account' => $Nature_account,
            'typeAccount' => $typeAccount,
            'User_id' => $User_id,
            'Type_migration' => $Type_migration,
            'AccountClass'=>$request->input('AccountClass')
        ]);
                          
        return response()->json(['success' => true, 'message' => 'تمت العملية بنجاح', 'DataSubAccount' => $mainAccount], 201);
    
    }

    public function update(Request $request, $id)
    {
            MainAccount::where('main_account_id',$id)
            ->update([
                'account_name'=>$request->account_name,
                'Nature_account'=>$request->Nature_account,
                'typeAccount'=>$request->typeAccount,
                'Type_migration'=>$request->Type_migration,
                'main_account_id'=>$request->main_account_id,
        ]);
        SubAccount::where('Main_id', $id)
    ->update([
        'typeAccount' => $request->typeAccount,
        'debtor_amount'=>$request->debtor_amount,
        'creditor_amount'=>$request->creditor_amount,
        // أي حقول أخرى يجب تحديثها في الحسابات الفرعية
    ]);

            return redirect()->route('Main_Account.create');
        }



    public function edit($id)
    {
        $account = MainAccount::where('main_account_id', $id)->first();
        return view('accounts.Main_Account.edit-main-account',['account'=>$account] );
    }


    public function storc(Request $request)
    {
        
            $Main_id = $request->Main_id;
            $DataSubAccount = new SubAccount();
            $TypeSubAccount = MainAccount::where('main_account_id', $Main_id)->first();
            $sub_name = $request->sub_name;
            $User_id = $request->User_id;
            $debtor_amount1 = $request->input('debtor_amount', '٠١٢٣٤٥٦٧٨٩');
            $creditor_amount1 = $request->input('creditor_amount', '٠١٢٣٤٥٦٧٨٩');
            $Phone1 = $this->convertArabicToEnglish($request->input('Phone', '٠١٢٣٤٥٦٧٨٩'));
            $Known_phone = $this->convertArabicToEnglish($request->input('Known_phone'));
            $name_The_known = $request->input('name_The_known');
    
            $creditor_amount = $this->convertArabicToEnglish($creditor_amount1);
            $debtor_amount = $this->convertArabicToEnglish($debtor_amount1);
    
            $account_names_exist = SubAccount::where('Main_id', $Main_id)->pluck('sub_name');
            if ($account_names_exist->contains($sub_name)) {
                return response()->json(['success' => false, 'message' => 'يوجد نفس هذا الاسم من قبل']);
            }
    // إعداد البيانات التي ترغب في استخدامها في عملية البحث أو الإنشاء
$dataSubAccountData = [
    'Main_id' => $Main_id,
    'sub_name' => $sub_name,
    'User_id' => $User_id,
    'AccountClass' => $TypeSubAccount->AccountClass,
    'typeAccount' => $TypeSubAccount->typeAccount,
    'debtor_amount' => $debtor_amount ?: 0,
    'creditor_amount' => $creditor_amount ?: 0,
    'Phone' => $Phone1 ?: null,
    'name_The_known' => $name_The_known ?: null,
    'Known_phone' => $Known_phone ?: null,
];

// استخدام firstOrCreate لإنشاء الكائن إذا لم يكن موجودًا
$DataSubAccount = SubAccount::firstOrCreate(
    [
        'sub_name' => $sub_name, // حقل فريد للبحث
        'Main_id' => $Main_id,   // حقل فريد آخر إذا كان موجودًا
    ],
    $dataSubAccountData // القيم التي سيتم استخدامها لإنشاء السجل إذا لم يكن موجودًا
);

// تحقق مما إذا تم حفظ الكائن بنجاح
if ($DataSubAccount->debtor_amount>0 || $DataSubAccount->creditor_amount>0) {
    $DSubAccount = SubAccount::where('sub_account_id', $DataSubAccount->sub_account_id)->first();
        $today = Carbon::now()->toDateString();
        $dailyPage = GeneralJournal::whereDate('created_at', $today)->latest()->first();
        
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();

    
        if (!$dailyPage) {
            $dailyPage = GeneralJournal::create([
                'accounting_period_id'=>$accountingPeriod->accounting_period_id,
            ]);
        }
        if (!$dailyPage || !$dailyPage->page_id) {
            return response()->json(['success' => false, 'message' => 'فشل في إنشاء صفحة يومية']);
        }

    $dailyEntry = DailyEntrie::create([
        'Amount_debit' => $DataSubAccount->debtor_amount  ?: 0,
        'account_debit_id' => $DSubAccount->sub_account_id ,
        'Amount_Credit' => $DataSubAccount->creditor_amount  ?: 0,
        'account_Credit_id' => $DSubAccount->sub_account_id ,
        'Statement' => 'إدخال رصيد افتتاحي',
        'Daily_page_id' =>$dailyPage->page_id,
        'Currency_name' => 'ر',
        'User_id' =>$User_id ,
        'Invoice_type' =>1,
        'accounting_period_id' =>$accountingPeriod->accounting_period_id,
        'Invoice_id' => $DSubAccount->sub_account_id ,
        'daily_entries_type' =>'رصيد افتتاحي',
        'status_debit' => 'غير مرحل',
        'status' => 'غير مرحل',
    ]);
    if ($dailyEntry) {

    return response()->json(['success'=>true,'message' => ' تم حفظ بنجاح ودخال مبلغ للحساب']);
}  
}  
  
return response()->json(['success'=>true,'message' => 'تم حفظ  بنجاح']);
          
    
    }
    

          
public function getSubAccounts(Request $request , $id)

{
    $subAccounts = SubAccount::where('Main_id', $id)->get();
    

    // إرجاع النتائج بصيغة JSON لاستخدامها في Select2
    return response()->json($subAccounts);
 
}

public function destroy($id){
    MainAccount::where('main_account_id',$id)->delete();
    return redirect()->back();
}

public function getMainAccountsByType($type)
{
    // التحقق مما إذا كان نوع الحساب الكبير موجود في Enum
    $accountType = AccountType::tryFrom($type);
    if (!$accountType) {
        return response()->json(['error' => 'نوع الحساب غير موجود'], 404);
    }
    // استرجاع الحسابات الرئيسية المرتبطة بالنوع
    $mainAccounts = MainAccount::where('typeAccount', $accountType->value)->get();

    return response()->json($mainAccounts);
}



}
