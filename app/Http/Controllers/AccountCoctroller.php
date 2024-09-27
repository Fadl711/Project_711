<?php

namespace App\Http\Controllers;

use App\Enum\AccountType;
use App\Enum\Deportatton;
use App\Enum\IntOrderStatus;
use App\Http\Controllers\Controller;
use App\Models\MainAccount;
use App\Models\SubAccount;
use Illuminate\Http\Request;

class AccountCoctroller extends Controller
{
    public function create(){
        
        $MainAccounts=MainAccount::all();
     

 return view(['MainAccounts'=> $MainAccounts]);
         }

    public function index(){
        $data=[
            ['idsec'=>'10','id'=>'1','sec'=>'العملاء','name'=>'جمال','pric'=>'$102'],
            ['idsec'=>'1','id'=>'8','sec'=>'البنك','name'=>'','pric'=>'$102'],
            ['idsec'=>'2','id'=>'2','sec'=>'الصندوق','name'=>'','pric'=>'$10225'],
            ['idsec'=>'4','id'=>'3','sec'=>'المبيعات','name'=>'','pric'=>'$102248'],
            ['idsec'=>'10','id'=>'4','sec'=>'العملاء','name'=>'سعيد','pric'=>'$10255'],
            ['idsec'=>'2','id'=>'5','sec'=>'الصندوق','name'=>'','pric'=>'$10255'],
            ['idsec'=>'1','id'=>'6','sec'=>'البنك','name'=>'','pric'=>'$102'],
            ['idsec'=>'10','id'=>'7','sec'=>'العملاء','name'=>'علي','pric'=>'$1155'],
            ['idsec'=>'10','id'=>'7','sec'=>'العملاء','name'=>'علي','pric'=>'$1155'],



            ];
            $post=MainAccount::all();


// return response()->json( $data);
      return  view('accounts.index',['posts'=>$data,'post'=> $post]);
        // return view('accounts.index');
    }
    public function getOptions( ){
        $options=[
            ['idsec'=>'10','id'=>'1','sec'=>'العملاء','name'=>'جمال','pric'=>'$102'],
            ['idsec'=>'1','id'=>'8','sec'=>'البنك','name'=>'','pric'=>'$102'],
            ['idsec'=>'2','id'=>'2','sec'=>'الصندوق','name'=>'','pric'=>'$10225'],
            ['idsec'=>'4','id'=>'3','sec'=>'المبيعات','name'=>'','pric'=>'$102248'],
            ['idsec'=>'10','id'=>'4','sec'=>'العملاء','name'=>'سعيد','pric'=>'$10255'],
            ['idsec'=>'2','id'=>'5','sec'=>'الصندوق','name'=>'','pric'=>'$10255'],
            ['idsec'=>'1','id'=>'6','sec'=>'البنك','name'=>'','pric'=>'$102'],
            ['idsec'=>'10','id'=>'7','sec'=>'العملاء','name'=>'علي','pric'=>'$1155'],
            ['idsec'=>'10','id'=>'7','sec'=>'العملاء','name'=>'علي','pric'=>'$1155'],



            ];
            $data=MainAccount::all();
return response()->json( $data);
    //   return view('accounts.account_balancing',['posts'=>$data]);
    }
    public function index_account_tree()
    {
        $MainAccounts=MainAccount::all();
        $SubAccount=SubAccount::all();
        $accountTypes = AccountType::cases(); // استرجاع كل القيم في Enum

        $accountsByType = [];

        // التكرار على كل نوع حساب واسترجاع الحسابات الرئيسية المرتبطة به
        foreach ($accountTypes as $accountType) {
            $accountsByType[$accountType->value] = MainAccount::where('typeAccount', $accountType->value)
                ->with('subAccounts')
                ->get();
        }

        return view('accounts.account_tree', compact('accountsByType', 'accountTypes'));
    
        // return view('accounts.account_tree',['MainAccounts'=> $MainAccounts,'SubAccount'=> $SubAccount]);
        // // return view('accounts.account_tree',['MainAccounts'=> $MainAccounts]);
    }
   
}
