<?php

namespace App\Providers;

use App\Enum\AccountClass;
use App\Enum\AccountType;
use App\Enum\Deportatton;
use App\Enum\IntOrderStatus;
use App\Enum\PaymentType;
use App\Enum\TransactionType;
use App\Models\BusinessData;
use App\Models\Category;
use App\Models\Currency;
use App\Models\MainAccount;
use App\Models\SubAccount;
use App\Models\CurrencySetting;
use App\Models\Default_customer;
use App\Models\PaymentBond;
use App\Models\Product;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::except([
            '*'
        ]);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            if (Schema::hasTable('users')) {
                $users = DB::table('users')->get();
            }

            $dataDeportattons=[
                ['Deportatton'=> (Deportatton::FINANCAL_CENTER_LIST ),'id'=>(IntOrderStatus::FINANCAL_CENTER_LIST )],
                ['Deportatton'=> (Deportatton::INCOME_STATEMENT),'id'=>(IntOrderStatus::INCOME_STATEMENT)],
            ];
            View::share('dataDeportattons', $dataDeportattons);

            $TypesAccountName=[
                ['TypesAccountName' => Deportatton::CURRENT_ASSETS, 'id' => AccountType::CURRENT_ASSETS],
                ['TypesAccountName' => Deportatton::FIXED_ASSETS, 'id' => AccountType::FIXED_ASSETS],
               ['TypesAccountName' => Deportatton::LIABILITIES_OPPONENTS, 'id' => AccountType::LIABILITIES_OPPONENTS],
               ['TypesAccountName' => Deportatton::EXPENSES, 'id' => AccountType::EXPENSES],
               ['TypesAccountName' => Deportatton::REVENUE, 'id' => AccountType::REVENUE],

            ];
            View::share('TypesAccountName', $TypesAccountName);

            if (Schema::hasTable('main_accounts')) {
                $mainAccount_Warehouse=MainAccount::where('AccountClass',AccountClass::STORE->value)->first();
                if ($mainAccount_Warehouse)
                {
                    $subAccount=SubAccount::where('Main_id',$mainAccount_Warehouse->main_account_id)->get();
                }
                View::share('Warehouse', $subAccount);
            }

            if (Schema::hasTable('products')) {
                $products = Product::all();
                if(isset($products)){
                    View::share([
                        'products' => $products,


                    ]);
                }
            }

            if (Schema::hasTable('main_accounts')) {
                $mainAccount_Supplier=MainAccount::where('AccountClass',AccountClass::SUPPLIER->value)->first();

                $subAccountSupplierid=SubAccount::where('AccountClass',AccountClass::SUPPLIER->value)->get();

                if(isset($subAccountSupplierid)){
                    View::share([
                        'subAccountSupplierid' => $subAccountSupplierid,


                    ]);
                }
            }

            if (Schema::hasTable('users')) {
                $users=User::all();
                View::share([
                    'users'=>$users,
                ]);
            }

            if (Schema::hasTable('user_permissions')) {
                $us= auth()->id();
                $use=UserPermission::where('User_id',$us)->get();
                if(isset($use))
                {
                    View::share([
                        'use'=>$use,
                        'user'=>$us,

                    ]);

                }
            }

            if (Schema::hasTable('business_data')) {
                $buss=BusinessData::first();
                View::share([
                    'buss'=>$buss,
                ]);
            }

            if (Schema::hasTable('currency_settings')) {
                $cu=CurrencySetting::first();
                View::share([
                    'cu'=>$cu,
                ]);
            }

            if (Schema::hasTable('default_customers')) {
                $Default_customers=Default_customer::first();
                View::share([
                    'Default_customers'=>$Default_customers,
                ]);
            }

            $transaction_typeExchangeBond="سند صرف";
            $transaction_typePaymentBonds="سند قبض";

            if (Schema::hasTable('payment_bonds')) {
                $PaymentBonds= PaymentBond::where('transaction_type',$transaction_typePaymentBonds)->get();
                $ExchangeBond= PaymentBond::where('transaction_type',$transaction_typeExchangeBond)->get();
                View::share([
                    'PaymentBonds'=>$PaymentBonds, 
                    'ExchangeBond'=>$ExchangeBond,
                ]);
            }

            if (Schema::hasTable('sub_accounts')) {
                $SubAccounts=SubAccount::all();
                View::share([
                    'SubAccounts'=>$SubAccounts,
                ]);
            }

            if (Schema::hasTable('main_accounts')) {
                $MainAccounts= MainAccount::all();
                View::share([
                    'MainAccounts'=>$MainAccounts,
                ]);
            }

            if (Schema::hasTable('currencies')) {
                $Currencies=Currency::all();
                View::share([
                    'Currencies'=>$Currencies,
                ]);
            }

            $accountClasses = AccountClass::cases();
            $PaymentType=PaymentType::cases();
            $transactionTypes = TransactionType::cases();
            $AccountType = AccountType::cases();
            View::share([
                'accountClasses'=>$accountClasses,
                'transactionTypes'=>$transactionTypes,
                'AccountTypes'=>$AccountType,
                'PaymentType'=>$PaymentType,
                'today '=> Carbon::now()->toDateString(),
            ]);

            if (Schema::hasTable('main_accounts')) {
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

                $currentDateTime = Carbon::now()->format('Y-m-d H:i:s');
                View::share([
                    'SubAccounts'=>$SubAccounts,
                    'TypesAccounts'=>$TypesAccountName,
                    'currentDateTime'=>$currentDateTime,

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

        } catch (\Exception $e) {
            // Log the error but don't prevent the application from booting
            \Log::error('Error in AppServiceProvider boot: ' . $e->getMessage());
        }
    }
}
