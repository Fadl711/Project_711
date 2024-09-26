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

// __________________________MainAccount______________________________________
$account_name= $request->account_name;
$typeAccount= $request->typeAccount;
$Nature_account= $request->Nature_account;
$Type_migration= $request->Type_migration;
       $debtor1 = $request->input('debtor', '٠١٢٣٤٥٦٧٨٩');
        $creditor1 = $request->input('creditor', '٠١٢٣٤٥٦٧٨٩');
        $Phone1 = $request->input('Phone', '٠١٢٣٤٥٦٧٨٩');
        $User_id= $request->User_id;
        $name_The_known=$request->name_The_known;
        $Known_phone=$request->Known_phone;
        $creditor=$this->convertArabicToEnglish($creditor1);
        $debtor=$this->convertArabicToEnglish($debtor1);
        
    $account_nametExists = MainAccount::where('account_name', $account_name)->exists();
            if ($account_nametExists)
              {
               return response()->json(['message'=>' هذا الاسم موجود من قبل']);
               }
           else {

         
// __________________________MainAccount______________________________________
       MainAccount::create([
     'account_name'=>$account_name,
     'Nature_account'=>$Nature_account,
     'typeAccount'=> $typeAccount,
     'User_id'=>$User_id,
     'Type_migration'=> $Type_migration]);
// __________________________ SubAccount ______________________________________

     $data=MainAccount::where('User_id',$User_id)->latest()->first();
     $DataSubAccount=new SubAccount();
        $DataSubAccount->Main_id=$data->main_account_id; 
        $DataSubAccount->sub_name=$account_name ;
        $DataSubAccount-> User_id= $User_id;
        $DataSubAccount->debtor = !empty($debtor) ? $debtor :0;
        $DataSubAccount-> creditor= !empty($creditor ) ? $creditor :0;
        $DataSubAccount->Phone = ($Phone1 ) ;
        $DataSubAccount-> name_The_known= ($name_The_known );
        $DataSubAccount->Known_phone = ($Known_phone ) ;
    $DataSubAccount->save();
   
    
return  response()->json(['message'=>'تمت العملية بنجاح"' ,'DataSubAccount'=>$DataSubAccount ]);




              }
             

            }
         


    

               
            
            
       


            public function storc(Request $request)
            {
                
$DataSubAccount=new SubAccount() ;

$sub_name= $request->sub_name;
// $DatamainAccount= $request->typeAccount;
$Main_id= $request->Main_id;
$User_id= $request->User_id;
// $DatamainAccount->Type_migration= $request->Type_migration;

                // $DataSubAccount=new SubAccount;
                $debtor1 = $request->input('debtor', '٠١٢٣٤٥٦٧٨٩');
                $creditor1 = $request->input('creditor', '٠١٢٣٤٥٦٧٨٩');
                $Phone1 = $request->input('Phone', '٠١٢٣٤٥٦٧٨٩');
        
        
               $creditor=$this->convertArabicToEnglish($creditor1);
         $debtor=$this->convertArabicToEnglish($debtor1);
                // $DatamainAccount= $request->User_id;
                
//     $data=SubAccount::where($Main_id)->get();
//    dd($data);
//    $data1=SubAccount::where($data->Main_id)->get();
  
 
           
    // $account_nametExists = SubAccount::where('sub_name', $sub_name)->exists();
    $account_nametExists = SubAccount::where('Main_id', $Main_id)->pluck('sub_name');
    foreach($account_nametExists as  $at_namet )
    {
if($at_namet==$sub_name)
{
    return response()->json(['message'=>' يوجد نفس هذا الاسم  من قبل']);
}
else{
    $DataSubAccount->Main_id=$Main_id; 
    $DataSubAccount->sub_name=$sub_name;
    $DataSubAccount->User_id= $User_id; 
    $DataSubAccount->debtor = !empty($debtor) ? $debtor :0;
    $DataSubAccount-> creditor= !empty($creditor ) ? $creditor :0;
    $DataSubAccount->Phone = !empty ($Phone1 ) ?$Phone1 : null ;
    $DataSubAccount-> name_The_known=!empty ($name_The_known) ? $name_The_known : null ;
    $DataSubAccount->Known_phone =!empty  ($Known_phone) ? $Known_phone : null ;
//    response()->json(['message'=>'تمت  بنجاح"' ,'post'=>$DataSubAccount,]);
// $idm=$DatamainAccount->main_account_id;
}

    }
    $DataSubAccount->save();//-


    
      
                return  response()->json(['message'=>'تمت العملية بنجاح"' ,'post'=>$DataSubAccount]);//-
            }
        

    
    
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


    public function stor(Request $request)
    { 
         $MainAccounts=MainAccount::all();
        //  dd( $MainAccounts);
        //  return view(['MainAccounts'=> $MainAccounts,'TypesAccounts'=> $dataTypesAccounts,'Deportattons'=> $dataDeportattons]);
        $sub_name=$request->sub_name;

        // $account_nametExists = SubAccount::where('sub_name', $sub_name)->exists();
        //     if ($account_nametExists)
        //       {
        //        return response()->json(['message'=>' هذا الاسم موجود من قبل']);
        //        }
        //    else {

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
           $DataSubAccount-> name_The_known=!empty ($name_The_known ) ?$name_The_known :null ;
           $DataSubAccount->Known_phone =!empty  ($Known_phone ) ?$Known_phone :null ;
       $DataSubAccount->save();
    // }
        $post=MainAccount::all();
    return response()->json(['message'=>'تمت العملية بنجاح"' ,'posts'=>$DataSubAccount ,'pos'=>$post]);
       
    }
}
