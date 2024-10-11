<?php

namespace App\Http\Controllers;

use App\Enum\AccountClass;
use App\Models\AccountingPeriod;
use Carbon\Carbon;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;

class HomeCoctroller extends Controller
{

    public function indxe(){
// $date=AccountingPeriod::all();
// if($date)
// {

//     $today = Carbon::now()->toDateString();
// //  $dailyPage = AccountingPeriod::whereDate('created_at', $today)->first();

//     // إذا لم توجد صفحة، قم بإنشائها
   
//       AccountingPeriod::create([
//       'Year'=>2024,
//       'Month'=>10,
//       '	Today'=>10,
//       ]);
   
//     return view('home.index');
// }else{
//     AccountingPeriod::create([
//         'Year'=>2024,
//         'Month'=>10,
//         '	Today'=>10,
//         ]);
    return view('home.index');
// }


    }
}
