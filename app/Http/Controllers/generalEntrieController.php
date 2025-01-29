<?php

namespace App\Http\Controllers;

use App\Models\DailyEntrie;
use App\Models\GeneralEntrie;
use Illuminate\Http\Request;

class generalEntrieController extends Controller
{
    //
    public function show($accounting_period_id)
    {
$generalentries=GeneralEntrie::where('accounting_period_id',$accounting_period_id)
->orderBy('id', 'asc') // ترتيب تصاعدي
->get();
$general_entries=[];
foreach ($generalentries as $generalentry) {

    $daily_entries=DailyEntrie::where('accounting_period_id',$accounting_period_id)->first();
    $general_entries[]=[
        'id'=>$generalentry->id,
        'sub_id'=>$generalentry->sub_id,
        'Main_id'=>$generalentry->Main_id,
        'Daily_entry_id'=>$generalentry->Daily_entry_id ??0,
        'Daily_Page_id'=>$generalentry->Daily_Page_id ??0,
        'User_id'=>$generalentry->User_id,
        'accounting_period_id'=>$generalentry->accounting_period_id,
        'entry_type'=>$generalentry->entry_type,
        'amount'=>$generalentry->amount,
        'entrie_id'=>$daily_entries->entrie_id ?? 0,
        'entry_type_text'=>($generalentry->entry_type=='debit'?'دا��ن':'مدين'),
    ];
}




    }
}
