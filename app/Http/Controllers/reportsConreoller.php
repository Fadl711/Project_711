<?php

namespace App\Http\Controllers;

use App\Events\DataChanged;
use App\Http\Controllers\Controller;
use App\Models\MainAccount;
use App\Models\SaleInvoice;
use App\Models\SubAccount;
use App\Providers\AppServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Mpdf\Tag\Main;

class reportsConreoller extends Controller
{
    public function summary(Request $request){
/*         if($request->type){

            dd($request->type);
            $type = $request->input('type'); // نوع البحث

            $query = Account::whereNotNull('parent_id'); // الحسابات الفرعية فقط

            if ($type === 'today') {
                $query->whereDate('created_at', Carbon::today());
            } elseif ($type === 'week') {
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            } elseif ($type === 'month') {
                $query->whereMonth('created_at', Carbon::now()->month)
                      ->whereYear('created_at', Carbon::now()->year);
            }
        } */
       $MainAccounts= MainAccount::all();
       $SubAccount= SubAccount::all();

        return view('report.Summary.summary',['name'=>'الحساب','SubAccount'=>$SubAccount,'MainAccounts'=>$MainAccounts]);
    }
    public function inventoryReport(){

        return view('report.InventoryReport.inventoryReport',['name'=>'المخزن']);
    }
    public function earningsReports(){
        return view('report.EarningsReports.earningsReports',['name'=>'الصنف','cond'=>false]);
    }
    public function salesReport(){
        $SaleInvoice=SaleInvoice::all();
        return view('report.SalesReport.salesReport',['name'=>'الصنف','cond'=>true,'SaleInvoice'=>$SaleInvoice]);
    }

    public function summaryPdf(){
        
        $MainAccounts= MainAccount::all();
        $SubAccount= SubAccount::all();
        return view('report.Summary.summaryPdf',['name'=>'الحساب','SubAccount'=>$SubAccount,'MainAccounts'=>$MainAccounts]);

    }
    public function earningsReportsPdf(){
        return view('report.EarningsReports.earningsReportsPdf');

    }
    public function inventoryReportPdf (){
        return view('report.InventoryReport.inventoryReportPdf');

    }

    public function salesReportPdf(){
        $SaleInvoice=SaleInvoice::all();
        return view('report.SalesReport.salesReportPdf',compact('SaleInvoice'));

    }
}
