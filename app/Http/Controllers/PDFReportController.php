<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;


class PDFReportController extends Controller
{
    public function createPDF()
    {

        $pdf = Pdf::loadView('inventory.index');

        return $pdf->download('report.pdf');





    }
    public function sales()
    {
        return view('invoice_sales.bills_sale_show');
    }
    public function purchase()
    {
        return view('invoice_purchases.bills_purchase_show');
    }

}


