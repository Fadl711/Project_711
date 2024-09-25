<?php

namespace App\Http\Controllers\Accounts\main_accounts;

use App\Enum\Deportatton;
use App\Enum\IntOrderStatus;
use App\Http\Controllers\Controller;
use App\Models\MainAccount;
use App\Models\SubAccount;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isNull;

class MainaccountController extends Controller
{
    public function create(){
        $post=MainAccount::all();
        $dataDeportattons=[
            ['Deportatton'=> (Deportatton::FINANCAL_CENTER_LIST ),'id'=>(IntOrderStatus::FINANCAL_CENTER_LIST )],
            ['Deportatton'=> (Deportatton::INCOME_STATEMENT),'id'=>(IntOrderStatus::INCOME_STATEMENT)],
 ];
 $dataIntOrderStatus=[
            ['Deportatton'=> (Deportatton::ASSETS),'id'=>(IntOrderStatus::ASSETS )],
            ['Deportatton'=> (Deportatton::LIABILITIES_OPPONENTS),'id'=>(IntOrderStatus::LIABILITIES_OPPONENTS)],
            ['Deportatton'=> (Deportatton::EXPENSES),'id'=>(IntOrderStatus::EXPENSES )],
            ['Deportatton'=> (Deportatton::REVENUE),'id'=>(IntOrderStatus::REVENUE )],
            
 ];
 
 return view('accounts.Main_Account.create',['pos'=> $post,'IntOrderStatus'=> $dataIntOrderStatus,'Deportattons'=> $dataDeportattons]);
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
    //   $request->validate([
    //     //     // 'Main_id' => 'required|exists:Main_id', 
    //          'account_name' => 'required|string|max:255',
    //        'Nature_account' => 'required|string|max:255',
    //        'Type_account_id' => 'required|integer|max:255',
    //    'User_id' => 'required|integer|max:255',
    // 'migration_ID' => 'required|string|max:255',

        
        //     // 'sub_name' => 'required|string|max:255',
        //     'debtor' => 'required|string|max:255',
        //     'creditor' => 'required|string|max:255',

        //     'debtor' => 'required|integer|min:0',
        //     'creditor' => 'required|integer|min:0',
        //     'creditor' => 'required|integer|min:0',
        //     'Phone' => 'required|string|max:255',
        //     'email' => 'required|string|max:255',
        //     'address' => 'required|string|max:255',
        //     'name_The_known' => 'required|string|max:255',
        //     'Known_phone' => 'required|string|max:255',


       
        //]);
      


// __________________________MainAccount______________________________________
$DatamainAccount=new MainAccount() ;

$account_name= $request->account_name;
$typeAccount= $request->typeAccount;
$Nature_account= $request->Nature_account;
$User_id= $request->User_id;
$Type_migration= $request->Type_migration;

// __________________________MainAccount______________________________________

    $account_nametExists = MainAccount::where('account_name', $account_name)->exists();
            if ($account_nametExists)
              {
               return response()->json(['message'=>' هذا الاسم موجود من قبل']);

               }
           else {
                  
                  // __________________________ SubAccount ______________________________________
        $DataSubAccount=new SubAccount;
        $debtor1 = $request->input('debtor', '٠١٢٣٤٥٦٧٨٩');
        $creditor1 = $request->input('creditor', '٠١٢٣٤٥٦٧٨٩');
        $Phone1 = $request->input('Phone', '٠١٢٣٤٥٦٧٨٩');
        $User_id= $request->User_id;


        $creditor=$this->convertArabicToEnglish($creditor1);
        $debtor=$this->convertArabicToEnglish($debtor1);

        $sub_name=$request->account_name;
        $name_The_known=$request->name_The_known;
        $Known_phone=$request->Known_phone;

       MainAccount::create(['account_name'=>$account_name
     ,'Nature_account'=>$Nature_account,
     'typeAccount'=> $typeAccount,'User_id'=>$User_id,
     'Type_migration'=> $Type_migration]);

     $data=MainAccount::where('User_id',$User_id)->latest()->first();
     $DataSubAccount= SubAccount::create(['Main_id' => $data->main_account_id, // Assuming $post is a collection of MainAccount models//+
     'sub_name' => $sub_name,//+
     'User_id' => $User_id,//+
     'debtor' => $debtor,//+
     'creditor' => $creditor,//+
     'Phone' => $Phone1,//+
     'name_The_known' => $name_The_known,//+
     'Known_phone' => $Known_phone]);
        // $DataSubAccount->save();main_account_id
        return  response()->json(['message'=>'تمت العملية بنجاح"' ,'post'=>$DataSubAccount ]);



              }
             

            }
         


    

               
            
            
       


            public function storc(Request $request)
            {
                
// // __________________________MainAccount______________________________________
// $DatamainAccount=new MainAccount() ;

// $account_name= $request->account_name;
// $typeAccount= $request->typeAccount;
// $Nature_account= $request->Nature_account;
// $User_id= $request->User_id;
// $Type_migration= $request->Type_migration;

//                 $DataSubAccount=new SubAccount;
//                 $debtor1 = $request->input('debtor', '٠١٢٣٤٥٦٧٨٩');
//                 $creditor1 = $request->input('creditor', '٠١٢٣٤٥٦٧٨٩');
//                 $Phone1 = $request->input('Phone', '٠١٢٣٤٥٦٧٨٩');
        
        
//                 $creditor=$this->convertArabicToEnglish($creditor1);
//                 $debtor=$this->convertArabicToEnglish($debtor1);
//                 $User_id= $request->User_id;
                
//                 $Main_id=$request->Main_id;

//                 $sub_name=$request->account_name;
//                 $name_The_known=$request->name_The_known;
//                 $Known_phone=$request->Known_phone;
//          //    response()->json(['message'=>'تمت  بنجاح"' ,'post'=>$DataSubAccount,]);
//         // $idm=$DatamainAccount->main_account_id;
//             // $DataSubAccount->save();//-
//                 return  response()->json(['message'=>'تمت العملية بنجاح"' ,'post'=>$DataSubAccount]);//-
// //-
// //-
//-
            }//-
        

    
    
    //
     // دالة البحث
    //  public function search(Request $request)
    //  {
    //      $account_name = $request->input('account_name'); // الحصول على النص المدخل في البحث
 
    //      // البحث عن المنتجات التي تحتوي على النص المدخل
    //      $accountname = MainAccount::where('account_name', 'LIKE', "%{$account_name}%")->get();
 
    //      // إرجاع النتائج كـ JSON
    //      return response()->json($accountname);
    //  }
}
