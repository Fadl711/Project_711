<?php

namespace App\Http\Controllers\Accounts;

use App\Enum\Deportatton;
use App\Enum\IntOrderStatus;
use App\Http\Controllers\Controller;
use App\Models\MainAccount;
use App\Models\SubAccount;
use Illuminate\Http\Request;

class SubaccountsController extends Controller
{

    public function create(){
        $post=MainAccount::all();


 return view('accounts.Sub_Account.create',['pos'=> $post]);
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
        $DataSubAccount= SubAccount::create(['main_id' =>$Main_id, 
        'sub_name' => $sub_name,//+
        'user_id' => $User_id,//+
        'debtor' => $debtor,//+
        'creditor' => $creditor,//+
        'phone' => $Phone1,//+
        'name_the_known' => $name_The_known,//+
        'known_phone' => $Known_phone]);

           return  response()->json(['message'=>'تمت العملية بنجاح' ,'post'=>$DataSubAccount ]);

    }
}
