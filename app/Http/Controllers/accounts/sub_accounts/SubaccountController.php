<?php

namespace App\Http\Controllers\Accounts\sub_accounts;

use App\Enum\AccountType;
use App\Enum\Deportatton;
use App\Enum\IntOrderStatus;
use App\Http\Controllers\Controller;
use App\Models\MainAccount;
use App\Models\SubAccount;
use Illuminate\Http\Request;

class SubaccountController extends Controller
{
    //
    
    public function create(){
        $dataDeportattons=[
            ['Deportatton'=> (Deportatton::FINANCAL_CENTER_LIST ),'id'=>(IntOrderStatus::FINANCAL_CENTER_LIST )],
            ['Deportatton'=> (Deportatton::INCOME_STATEMENT),'id'=>(IntOrderStatus::INCOME_STATEMENT)],
 ];
 $TypesAccountName = [

    ['TypesAccountName' => Deportatton::FIXED_ASSETS, 'id' => AccountType::FIXED_ASSETS],
    ['TypesAccountName' => Deportatton::CURRENT_ASSETS, 'id' => AccountType::CURRENT_ASSETS],
    ['TypesAccountName' => Deportatton::LIABILITIES_OPPONENTS, 'id' => AccountType::LIABILITIES_OPPONENTS],
    ['TypesAccountName' => Deportatton::EXPENSES, 'id' => AccountType::EXPENSES],
    ['TypesAccountName' => Deportatton::REVENUE, 'id' => AccountType::REVENUE],
 ];

 $MainAccounts=MainAccount::all();

//  dd( $MainAccounts);

 return view('accounts.Main_Account.create-sub-account',['MainAccounts'=> $MainAccounts,'TypesAccounts'=> $TypesAccountName,'Deportattons'=> $dataDeportattons]);
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
         $MainAccounts=MainAccount::all();
        //  dd( $MainAccounts);
        //  return view(['MainAccounts'=> $MainAccounts,'TypesAccounts'=> $dataTypesAccounts,'Deportattons'=> $dataDeportattons]);
        $sub_name=$request->sub_name;

        $account_nametExists = SubAccount::where('sub_name', $sub_name)->exists();
            if ($account_nametExists)
              {
               return response()->json(['message'=>' هذا الاسم موجود من قبل']);
               }
           else {

        $debtor1 = $request->input('debtor', '٠١٢٣٤٥٦٧٨٩');
        $creditor1 = $request->input('creditor', '٠١٢٣٤٥٦٧٨٩');
        $Phone1 = $request->input('Phone', '٠١٢٣٤٥٦٧٨٩');
        $User_id= $request->User_id; 
         $sub_name=$request->sub_name;
        $name_The_known=$request->name_The_known;
        $Known_phone=$request->Known_phone;
        $creditor=$this->convertArabicToEnglish($creditor1);
        $debtor=$this->convertArabicToEnglish($debtor1);
        $Main_id= $request->Main_id;
        $sub_name= $request->sub_name;

    // $data=SubAccount::where('Main_id',$Main_id)->latest()->first();

    // $data1=SubAccount::where($data->Main_id);
    //  dd($data1);
 
           
    // $account_nametExists = MainAccount::where('sub_name', $sub_name)->where('')->exists();
    // if ($account_nametExists)
    //   {
    //    return response()->json(['message'=>' هذا الاسم موجود من قبل']);
    //    }
        $DataSubAccount=new SubAccount();
           $DataSubAccount->Main_id=$Main_id; 
           $DataSubAccount->sub_name=$sub_name;
           $DataSubAccount->User_id= $User_id; 
           $DataSubAccount->debtor = !empty($debtor) ? $debtor :0;
           $DataSubAccount-> creditor= !empty($creditor ) ? $creditor :0;
           $DataSubAccount->Phone = !empty ($Phone1 ) ?$Phone1 :null ;
           $DataSubAccount-> name_The_known=$name_The_known  ?$name_The_known :null ;
           $DataSubAccount->Known_phone = $Known_phone  ?$Known_phone :null ;
       $DataSubAccount->save();
    }
        $post=MainAccount::all();
    return response()->json(['message'=>'تمت العملية بنجاح"' ,'posts'=>$DataSubAccount ,'pos'=>$post]);
       
    }
}
