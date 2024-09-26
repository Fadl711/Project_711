<?php

namespace App\Providers;

use App\Models\BusinessData;
use App\Models\Category;
use App\Models\CurrencySetting;
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

        $cate=Category::all();
        $buss=BusinessData::all()->first();
        $cu=CurrencySetting::first();
        View::share([
            'cate'=>$cate,
            'cu'=>$cu,
        ]);
        if(isset($buss)){

            View::share([
                'com_name'=> $buss->Company_Name,
                'com_nameE'=> $buss->Company_NameE,
                'com_for'=> $buss->Services,
                'com_forE'=> $buss->ServicesE,
                'com_phones'=> $buss->Phone_Number,
                'com_address'=> $buss->Company_Address,
                'com_addressE'=> $buss->Company_AddressE,
                'com_photo'=>$buss->Company_Logo,

        ]);
        }
    }
}
