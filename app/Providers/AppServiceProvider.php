<?php

namespace App\Providers;

use App\Enum\AccountType;
use App\Enum\Deportatton;
use App\Enum\IntOrderStatus;
use App\Models\BusinessData;
use App\Models\Category;
use App\Models\MainAccount;
use App\Models\SubAccount;
use App\Models\CurrencySetting;
use App\Models\User;
use Illuminate\Support\Carbon;
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
        $TypesAccountName=[
            ['TypesAccountName' => Deportatton::CURRENT_ASSETS, 'id' => AccountType::CURRENT_ASSETS],
            ['TypesAccountName' => Deportatton::FIXED_ASSETS, 'id' => AccountType::FIXED_ASSETS],
           ['TypesAccountName' => Deportatton::LIABILITIES_OPPONENTS, 'id' => AccountType::LIABILITIES_OPPONENTS],
           ['TypesAccountName' => Deportatton::EXPENSES, 'id' => AccountType::EXPENSES],
           ['TypesAccountName' => Deportatton::REVENUE, 'id' => AccountType::REVENUE],

        ];
        $today = Carbon::now()->toDateString();  // الحصول على تاريخ اليوم بصيغة YYYY-MM-DD

    // $dailyPage = GeneralJournal::whereDate('created_at', $today)->first(); // البحث عن الصفحة
        $users=User::all();
        View::share([

            'TypesAccounts'=>$TypesAccountName,
            'Deportattons'=>$dataDeportattons,
            'today '=> Carbon::now()->toDateString(),
            'users'=>$users,
        ]);

        $cate=Category::all();
        $buss=BusinessData::first();
        $cu=CurrencySetting::first();

        View::share([
            'cate'=>$cate,
            'cu'=>$cu,
            'buss'=>$buss,

        ]);
        if(isset($buss)){

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

}
    }
}

