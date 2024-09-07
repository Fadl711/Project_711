<?php

namespace App\Http\Controllers\chartController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChartController extends Controller
{
    public function index(){
        return view('chart.index');

    }
    public function getBarChartDate(Request $request)
{


/*     $po=productt::find($request->country);
    $countryData = sale::where('prod', $po->id)
    // use When for filtering by date
    ->when($request->from, function ($query) use ($request) {
        // When the Request has from then this method will be called
        return $query->whereDate('date', '>=', $request->from);
    })
    ->when($request->to, function ($query) use ($request) {
        // When the Request has to then this method will be called
        return $query->whereDate('date', '<=', $request->to);
    })
    ->selectRaw('SUM(qua) as Confirmed')
    ->first();
    $x=$countryData->Confirmed * $po->price_bougit;
    $s=$x- $countryData->Confirmed * $po->price_sals ; */

// to get the country Name From Country Table


return response()->json(['foo' => 500,'no'=>[65, 78, 66, 44, 56, 67, 75]]);


}
}
