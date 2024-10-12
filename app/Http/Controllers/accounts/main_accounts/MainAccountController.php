<?php

namespace App\Http\Controllers\Accounts\main_accounts;

use App\Enum\AccountType;
use App\Enum\Deportatton;
use App\Enum\IntOrderStatus;
use App\Http\Controllers\Controller;
use App\Models\AccountingPeriod;
use App\Models\DailyEntrie;
use App\Models\MainAccount;
use App\Models\SubAccount;
use Illuminate\Support\Facades\Db;

// use Illuminate\Container\Attributes\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;

use function Laravel\Prompts\select;
use function PHPUnit\Framework\isNull;

class MainaccountController extends Controller
{
    public function editMainAccount(){



    }
    public function create(){
        $mainAccount=MainAccount::all();//
        $subAccount = SubAccount::all();
        $dataDeportattons=[
            ['Deportatton'=> (Deportatton::FINANCAL_CENTER_LIST ),'id'=>(IntOrderStatus::FINANCAL_CENTER_LIST )],
            ['Deportatton'=> (Deportatton::INCOME_STATEMENT),'id'=>(IntOrderStatus::INCOME_STATEMENT)],
 ];
 $TypesAccountName=[
     ['TypesAccountName' => Deportatton::CURRENT_ASSETS, 'id' => AccountType::CURRENT_ASSETS],
     ['TypesAccountName' => Deportatton::FIXED_ASSETS, 'id' => AccountType::FIXED_ASSETS],
    ['TypesAccountName' => Deportatton::LIABILITIES_OPPONENTS, 'id' => AccountType::LIABILITIES_OPPONENTS],
    ['TypesAccountName' => Deportatton::EXPENSES, 'id' => AccountType::EXPENSES],
    ['TypesAccountName' => Deportatton::REVENUE, 'id' => AccountType::REVENUE],

 ];
return view('accounts.Main_Account.create',[ 'mainAccounts'=>$mainAccount,'subAccounts'=>$subAccount,'TypesAccounts'=> $TypesAccountName,'Deportattons'=> $dataDeportattons]);  }

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

      // __________________________ SubAccount ______________________________________
        $data=MainAccount::where('User_id',$User_id)->latest()->first();
        $DataSubAccount=new SubAccount();
        $DataSubAccount->Main_id=$data->main_account_id;
        $DataSubAccount->sub_name=$account_name ;
        $DataSubAccount-> User_id= $User_id;
        $DataSubAccount->debtor_amount = !empty($debtor_amount) ? $debtor_amount :0;
        $DataSubAccount-> creditor_amount= !empty($creditor_amount ) ? $creditor_amount :0;
        $DataSubAccount->Phone = ($Phone1 ) ;
        $DataSubAccount-> name_The_known= !empty($name_The_known ) ? $name_The_known : null ;
        $DataSubAccount->Known_phone = !empty($Known_phone ) ? $Known_phone : null ;
        $DataSubAccount->save();
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

            return redirect()->route('Main_Account.create');
        }



    public function edit($id)
    {
        $account = MainAccount::where('main_account_id', $id)->first();
        return view('accounts.Main_Account.edit-main-account',['account'=>$account] );
    }


    public function storc(Request $request)
{
    // إنشاء كائن جديد من SubAccount
    $DataSubAccount = new SubAccount();

    // استرجاع البيانات من الطلب
    $sub_name = $request->sub_name;
    $Main_id = $request->Main_id;
    $User_id = $request->User_id;

    // تحويل الأرقام من العربي إلى الإنجليزي
    $debtor_amount1 = $request->input('debtor_amount', '٠١٢٣٤٥٦٧٨٩');
    $creditor_amount1 = $request->input('creditor_amount', '٠١٢٣٤٥٦٧٨٩');
    $Phone1 = $request->input('Phone', '٠١٢٣٤٥٦٧٨٩');
    $Known_phone = $request->input('Known_phone');
    $name_The_known = $request->input('name_The_known');

    $creditor_amount = $this->convertArabicToEnglish($creditor_amount1);
    $debtor_amount = $this->convertArabicToEnglish($debtor_amount1);

    /// التحقق من وجود نفس الاسم في قاعدة البيانات
$account_names_exist = SubAccount::where('Main_id', $Main_id)->pluck('sub_name');

// إذا وجد الاسم بالفعل، إرجاع استجابة خطأ
if ($account_names_exist->contains($sub_name)) {
    return response()->json(['success' => false, 'message' => 'يوجد نفس هذا الاسم من قبل']);
}
else{


    // تعيين القيم في كائن SubAccount
    $DataSubAccount->Main_id = $Main_id;
    $DataSubAccount->sub_name = $sub_name;
    $DataSubAccount->User_id = $User_id;
    $DataSubAccount->debtor_amount = !empty($debtor_amount) ? $debtor_amount : 0;
    $DataSubAccount->creditor_amount = !empty($creditor_amount) ? $creditor_amount : 0;
    $DataSubAccount->Phone = !empty($Phone1) ? $Phone1 : null;
    $DataSubAccount->name_The_known = !empty($name_The_known) ? $name_The_known : null;
    $DataSubAccount->Known_phone = !empty($Known_phone) ? $Known_phone : null;

    // حفظ البيانات في قاعدة البيانات
    $DataSubAccount->save();

    // إرجاع استجابة نجاح
    return response()->json(['success' => true, 'message' => 'تمت العملية بنجاح', 'DataSubAccount' => $DataSubAccount]);
}
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
public function review_budget($year, $month)
{
    // جلب الفترة المحاسبية المحددة
    $accountingPeriod = AccountingPeriod::where('Year', $year)
        ->where('Month', $month)
        ->first();

    // التحقق من وجود الفترة المحاسبية
    if (!$accountingPeriod) {
        return response()->json(['error' => 'الفترة المحاسبية غير موجودة'], 404);
    }

    // استرجاع الحسابات الرئيسية مع حساباتها الفرعية وتجميع الأرصدة
    $mainAccountsTotals = MainAccount::with(['subAccounts' => function($query) {
        $query->select(
            'sub_accounts.sub_account_id',
            'sub_accounts.Main_id',
            'sub_accounts.sub_name',
            'sub_accounts.debtor_amount',
            'sub_accounts.creditor_amount'
        )
        ->leftJoin('daily_entries AS debit', 'sub_accounts.sub_account_id', '=', 'debit.account_debit_id')
        ->leftJoin('daily_entries AS credit', 'sub_accounts.sub_account_id', '=', 'credit.account_Credit_id')
        ->selectRaw('
            sub_accounts.sub_account_id,
            sub_accounts.Main_id,
            sub_accounts.sub_name,
            sub_accounts.debtor_amount,
            sub_accounts.creditor_amount,
            SUM(IFNULL(sub_accounts.debtor_amount, 0) + IFNULL(debit.Amount_debit, 0)) as total_debit,
            SUM(IFNULL(sub_accounts.creditor_amount, 0) + IFNULL(credit.Amount_credit, 0)) as total_credit
        ')
        ->groupBy('sub_accounts.sub_account_id', 'sub_accounts.Main_id', 'sub_accounts.sub_name', 'sub_accounts.debtor_amount', 'sub_accounts.creditor_amount');
    }])->get();
    


    // تمرير البيانات إلى العرض
    return view('accounts.review-budget', [
        'mainAccountsTotals' => $mainAccountsTotals,
        'accountingPeriod' => $accountingPeriod, 
    ]);
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
