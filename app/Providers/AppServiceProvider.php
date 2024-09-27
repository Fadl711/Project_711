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

        $dataDeportattons=[
            ['Deportatton'=> (Deportatton::FINANCAL_CENTER_LIST ),'id'=>(IntOrderStatus::FINANCAL_CENTER_LIST )],
            ['Deportatton'=> (Deportatton::INCOME_STATEMENT),'id'=>(IntOrderStatus::INCOME_STATEMENT)],
 ];
 $dataTypesAccounts=[
            ['TypesAccount'=> (Deportatton::ASSETS ),'id'=>(AccountType::ASSETS )],
            ['TypesAccount'=> (Deportatton::LIABILITIES_OPPONENTS),'id'=>(AccountType::LIABILITIES_OPPONENTS)],
            ['TypesAccount'=> (Deportatton::EXPENSES ),'id'=>(AccountType::EXPENSES )],
            ['TypesAccount'=> (Deportatton::REVENUE ),'id'=>(AccountType::REVENUE )],

 ];

 $SubAccount=SubAccount::all();
 $MainAccount= MainAccount::all();
 View::share([
         'TypesAccount'=>$dataTypesAccounts,
         'Deportattons'=>$dataDeportattons,
         'SubAccount'=>$SubAccount,
         'MainAccount'=>$MainAccount,

        
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
