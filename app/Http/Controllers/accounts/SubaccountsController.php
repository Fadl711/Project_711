<?php

namespace App\Http\Controllers\Accounts;

use App\Enum\Deportatton;
use App\Enum\IntOrderStatus;
use App\Http\Controllers\Controller;
use App\Models\MainAccount;
use App\Models\Sub_Account;
use App\Models\SubAccount;
use Illuminate\Http\Request;

class SubaccountsController extends Controller
{
    
    public function create(){
        $post=MainAccount::all();
        $dataDeportattons=[
            ['Deportatton'=> (Deportatton::FINANCAL_CENTER_LIST ),'id'=>(IntOrderStatus::FINANCAL_CENTER_LIST )],
            ['Deportatton'=> (Deportatton::INCOME_STATEMENT),'id'=>(IntOrderStatus::INCOME_STATEMENT)],
 ];
 $dataTypesAccounts=[
            ['TypesAccount'=> (Deportatton::ASSETS ),'id'=>(IntOrderStatus::ASSETS )],
            ['TypesAccount'=> (Deportatton::LIABILITIES_OPPONENTS),'id'=>(IntOrderStatus::LIABILITIES_OPPONENTS)],
            ['TypesAccount'=> (Deportatton::EXPENSES ),'id'=>(IntOrderStatus::EXPENSES )],
            ['TypesAccount'=> (Deportatton::REVENUE ),'id'=>(IntOrderStatus::REVENUE )],
            
 ];
 
 return view('accounts.Sub_Account.create',['pos'=> $post,'TypesAccounts'=> $dataTypesAccounts,'Deportattons'=> $dataDeportattons]);
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
        // قم بالتحقق من صحة البيانات'
        // $request->validate([
        //     // 'Main_id' => 'required|exists:Main_id', 

        //     'sub_name' => 'required|string|max:255',
        //     'debtor' => 'required|integer|min:1',
        //     'creditor' => 'required|integer|min:0',
        // ]);

        $debtor1 = $request->input('debtor', '٠١٢٣٤٥٦٧٨٩');
        $creditor1 = $request->input('creditor', '٠١٢٣٤٥٦٧٨٩');
        $Phone1 = $request->input('Phone', '٠١٢٣٤٥٦٧٨٩');
        $User_id= $request->User_id;  $sub_name=$request->account_name;
        $name_The_known=$request->name_The_known;
        $Known_phone=$request->Known_phone;
        $creditor=$this->convertArabicToEnglish($creditor1);
        $debtor=$this->convertArabicToEnglish($debtor1);
        $Main_id= $request->Main_id;
        $sub_name= $request->sub_name;

        $DataSubAccount= SubAccount::create(['Main_id' =>$Main_id, // Assuming $post is a collection of MainAccount models//+
        'sub_name' => $sub_name,//+
        'User_id' => $User_id,//+
        'debtor' => $debtor,//+
        'creditor' => $creditor,//+
        'Phone' => $Phone1,//+
        'name_The_known' => $name_The_known,//+
        'Known_phone' => $Known_phone]);
           // $DataSubAccount->save();main_account_id
           return  response()->json(['message'=>'تمت العملية بنجاح"' ,'post'=>$DataSubAccount ]);
   
        $post=MainAccount::all();
     return response()->json(['message'=>'تمت العملية بنجاح"' ,'posts'=>$DataSubAccount]);
    }
}
