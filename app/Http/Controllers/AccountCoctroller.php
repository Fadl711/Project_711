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


      return  view('accounts.index',['posts'=>$data,'post'=> $post]);
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
        
        $ASSETS=AccountType::FIXED_ASSETS;

        $LIABILITIES_OPPONENTS=AccountType::LIABILITIES_OPPONENTS;
        $EXPENSES=AccountType::EXPENSES;
        $REVENUE=AccountType::REVENUE;
        $Assets=MainAccount::where('typeAccount',$ASSETS)->get();
        $LIABILITIES_OPPONENTS=MainAccount::where('typeAccount',$LIABILITIES_OPPONENTS)->get();

        $TypesAccountName = [

            ['TypesAccountName' => Deportatton::FIXED_ASSETS, 'id' => AccountType::FIXED_ASSETS],
            ['TypesAccountName' => Deportatton::CURRENT_ASSETS, 'id' => AccountType::CURRENT_ASSETS],
            ['TypesAccountName' => Deportatton::LIABILITIES_OPPONENTS, 'id' => AccountType::LIABILITIES_OPPONENTS],
            ['TypesAccountName' => Deportatton::EXPENSES, 'id' => AccountType::EXPENSES],
            ['TypesAccountName' => Deportatton::REVENUE, 'id' => AccountType::REVENUE],
         ];

       
        return view('accounts.account_tree', ['Assets'=>$Assets,'TypesAccounts'=> $TypesAccountName,]);
    
    }
   
}
