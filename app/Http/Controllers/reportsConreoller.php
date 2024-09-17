<?php

namespace App\Http\Controllers;

use App\Events\DataChanged;
use App\Http\Controllers\Controller;
use App\Providers\AppServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class reportsConreoller extends Controller
{
    public function summary(){
        return view('report.Summary.summary',['name'=>'الحساب']);
    }
    public function inventoryReport(){

        return view('report.InventoryReport.inventoryReport',['name'=>'المخزن']);
    }
    public function earningsReports(){
        return view('report.EarningsReports.earningsReports',['name'=>'الصنف','cond'=>false]);
    }
    public function salesReport(){
        return view('report.SalesReport.salesReport',['name'=>'الصنف','cond'=>true]);
    }

    public function summaryPdf(){
        return view('report.Summary.summaryPdf');

    }
    public function earningsReportsPdf(){
        return view('report.EarningsReports.earningsReportsPdf');

    }
    public function inventoryReportPdf (){
        return view('report.InventoryReport.inventoryReportPdf');

    }
    public function salesReportPdf(){
        return view('report.SalesReport.salesReportPdf');

    }
}
