<?php

namespace App\Providers;

use App\Enum\AccountType;
use App\Enum\Deportatton;
use App\Enum\IntOrderStatus;
use App\Models\BusinessData;
use App\Models\Category;
use App\Models\MainAccount;
use App\Models\SubAccount;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $TypesAccountName=[
            ['TypesAccountName' => Deportatton::CURRENT_ASSETS, 'id' => AccountType::CURRENT_ASSETS],
            ['TypesAccountName' => Deportatton::FIXED_ASSETS, 'id' => AccountType::FIXED_ASSETS],
           ['TypesAccountName' => Deportatton::LIABILITIES_OPPONENTS, 'id' => AccountType::LIABILITIES_OPPONENTS],
           ['TypesAccountName' => Deportatton::EXPENSES, 'id' => AccountType::EXPENSES],
           ['TypesAccountName' => Deportatton::REVENUE, 'id' => AccountType::REVENUE],
       
        ];


$ASSETSAccountType_id=AccountType::FIXED_ASSETS;
 $ASSETSAccountType2=Deportatton::CURRENT_ASSETS;
 $MainAccount= MainAccount::where('typeAccount',$ASSETSAccountType_id)->get();

 $LIABILITIes_OPPONENtsAccountType_id=AccountType::LIABILITIES_OPPONENTS;
 $LIABILITIes_OPPONENtsAccountType_name=Deportatton::LIABILITIES_OPPONENTS;
 $MainAccount2= MainAccount::where('typeAccount',$LIABILITIes_OPPONENtsAccountType_id)->get();

 $EXPENSES_id=AccountType::EXPENSES;
 $EXPENSES_name=Deportatton::EXPENSES;
 $MainAccount3= MainAccount::where('typeAccount',$EXPENSES_id)->get();

 
 $REVENUE_id=AccountType::REVENUE;
 $REVENUE_name=Deportatton::REVENUE;
 $MainAccount4= MainAccount::where('typeAccount',$REVENUE_id)->get();

 $SubAccounts=SubAccount::all();


//  $SubAccounts= SubAccount::where('Main_id',$ $MainAccount->main_account_id)->get();

 View::share([
    'SubAccounts'=>$SubAccounts,
    'TypesAccounts'=>$TypesAccountName,

       'LIABILITIes_OPPONENtsAccountType_id'=>$LIABILITIes_OPPONENtsAccountType_id ,
       'LIABILITIes_OPPONENtsAccountType_name'=>$LIABILITIes_OPPONENtsAccountType_name ,
       'MainAccount2'=>$MainAccount2,


         
         'ASSETSAccountType2'=>$ASSETSAccountType2,
         'ASSETSAccountType_id'=>$ASSETSAccountType_id,
         'MainAccount'=>$MainAccount,



         'EXPENSES_name'=>$EXPENSES_name,
         'EXPENSES_id'=>$EXPENSES_id,
         'MainAccount3'=>$MainAccount3,

         'REVENUE_name'=>$REVENUE_name,
         'REVENUE_id'=>$REVENUE_id,
         'MainAccount4'=>$MainAccount4,



        
    ]);

    // $isTableEmpty = SubAccount::count() === 0;
    // if ($isTableEmpty) 
    // {


    // }
   

        // $cate=Category::all();
        // $buss=BusinessData::all()->first();
        // View::share([
        //     'cate'=>$cate,
        // ]);
        // if(isset($buss)){

        //     View::share([
        //         'com_name'=> $buss->Company_Name,
        //         'com_nameE'=> $buss->Company_NameE,
        //         'com_for'=> $buss->Services,
        //         'com_forE'=> $buss->ServicesE,
        //         'com_phones'=> $buss->Phone_Number,
        //         'com_address'=> $buss->Company_Address,
        //         'com_addressE'=> $buss->Company_AddressE,
        //         'com_photo'=>$buss->Company_Logo,

        // ]);
        // }
    }
}
