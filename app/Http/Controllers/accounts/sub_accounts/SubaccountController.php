<?php

namespace App\Http\Controllers\Accounts\sub_accounts;

use App\Enum\AccountType;
use App\Enum\Deportatton;
use App\Enum\IntOrderStatus;
use App\Http\Controllers\Controller;
use App\Models\AccountingPeriod;
use App\Models\DailyEntrie;
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

 return view('accounts.Sub_Accounts.create-sub-account',['MainAccounts'=> $MainAccounts,'TypesAccounts'=> $TypesAccountName,'Deportattons'=> $dataDeportattons]);
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
    public function edit($id){
        $SubAccount=SubAccount::where('sub_account_id',$id)->first();
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->firstOrFail();
        $transaction_type="رصيد افتتاحي";

        $Getentrie_id = DailyEntrie::where('Invoice_id',$SubAccount->sub_account_id)
        ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
        ->where('daily_entries_type',$transaction_type)
            ->first();
            // dd($Getentrie_id);

        return view('accounts.Sub_Accounts.edit',compact('SubAccount','Getentrie_id'));
    }
    public function update(Request $request){
        $transaction_type="رصيد افتتاحي";
        SubAccount::where('sub_account_id',$request->sub_id)->update([
            'sub_name'=>$request->sub_name,
            'Main_id'=>$request->Main_id,
            'User_id'=>$request->User_id,
            'debtor_amount'=>$request->debtor_amount,
            'creditor_amount'=>$request->creditor_amount,
            'Phone' =>$request->Phone ,
            'name_The_known' =>$request->name_The_known ,
            
        ]);
        $SubAccount = SubAccount::where('sub_account_id', $request->sub_id)->first();
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->firstOrFail();


            if($SubAccount->debtor_amount!=0  || $SubAccount->creditor_amount!=0  )
            {
                $Getentrie_id = DailyEntrie::where('Invoice_id',$SubAccount->sub_account_id)
                ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
                ->where('daily_entries_type',$transaction_type)
                ->update([
                'Amount_debit' => $SubAccount->debtor_amount,
                'Amount_Credit' => $SubAccount->creditor_amount,
            ]);

        }



        return redirect()->route('subAccounts.allShow');
    }
    public function destroy($id){
        if($id == 1 || $id ==2){
            return redirect()->back();

        }else{

            SubAccount::where('sub_account_id',$id)->delete();
            $transaction_type="رصيد افتتاحي";
            // إعداد بيانات الإدخالات اليومية
            $accountingPeriod = AccountingPeriod::where('is_closed', false)->firstOrFail();

            $Getentrie_id = DailyEntrie::where('Invoice_id',$id)
            ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
            ->where('daily_entries_type',$transaction_type)
            ->value('entrie_id');

            return redirect()->back();

        }
    }
    public function allShow(){
        $SubAccounts=SubAccount::all();
        return view('accounts.Sub_Accounts.all_show_subAccount',compact('SubAccounts'));
    }
}
