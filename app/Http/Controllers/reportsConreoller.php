<?php

namespace App\Http\Controllers;

use App\Events\DataChanged;
use App\Http\Controllers\Controller;
use App\Models\AccountingPeriod;
use App\Models\MainAccount;
use App\Models\Product;
use App\Models\Sale;
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
    public function create(Request $request){
               $MainAccounts= MainAccount::all();
               $SubAccount= SubAccount::all();

                return view('report.create',compact('MainAccounts','SubAccount'));
            }
    public function inventoryReport(){

        return view('report.InventoryReport.inventoryReport',['name'=>'المخزن']);
    }
    public function earningsReports(){
        return view('report.EarningsReports.earningsReports',['name'=>'الصنف','cond'=>false]);
    }
    public function salesReport_create(){
        $class=1;
        $SubAccountCostmers= SubAccount::where('AccountClass',1)->get();

        $Products=Product::all();
        return view('salesReport.create',compact('SubAccountCostmers','Products'));

    }
    public function print(Request $request)
    {
   // التحقق من المدخلات
   $validated = $request->validate([
       'accountList' => 'nullable|',
       'sub_account_id' => 'nullable|integer', // إذا كان معرف العميل
       'product_id' => 'nullable|',
    // 'accountingPeriodData' => 'nullable|',
    'Quantit' => 'nullable|',
    'DisplayMethod' => 'nullable|string|max:255',
]);


// dd($validated['accountList']);
return $this->salesProfitReport($validated);


    }

    public function salesProfitReport($validated){
        $class=1;
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
        $balancesSale = Sale::where('transaction_type',4);
        $SubAccountCostmers= SubAccount::where('AccountClass',$class)->get();
        // $balances = Sale::selectRaw(
        //     '
        //     SUM(CASE WHEN sales.Customer_id = sub_accounts.sub_account_id  THEN sales.total_profit  ELSE 0 END) as total_profit4,
        //     SUM(CASE WHEN sales.Customer_id = sub_accounts.sub_account_id  THEN sales.discount ELSE 0 END) as discount4,
        //     SUM(CASE WHEN sales.Customer_id = sub_accounts.sub_account_id  THEN sales.total_profit  ELSE 0 END) as total_profit5,
        //     SUM(CASE WHEN sales.Customer_id = sub_accounts.sub_account_id  THEN sales.discount ELSE 0 END) as discount5,

        // ')
        // ->join('sub_accounts', function ($join) {
        //     $join->on('sales.Customer_id', '=', 'sub_accounts.sub_account_id');
        // })
        // ->where('sales.accounting_period_id', $accountingPeriod->accounting_period_id)
        // ->get();
        if( $validated['DisplayMethod']==="SelectedProduct")
        {
            $uniqueProducts = Product::where('product_id', $validated['product_id'])->get();
        }
        if( $validated['DisplayMethod']==="ShowAllProducts")
        {
            $uniqueProducts = Product::all();
        }
        $sub_accountT4=null;
        $sub_accountT5=null;

        if( $validated['accountList']==="mainAccount")
        {
            $sub_accountT4= $validated['sub_account_id'];
            $sub_accountT5=$validated['sub_account_id'];
        }
$dataProducts=[];
$dailyTotals=[];

        foreach($uniqueProducts as $uniqueProduct )
        {
            
            $query = Sale::where('accounting_period_id', $accountingPeriod->accounting_period_id)->where('transaction_type',4);
            $querys = Sale::where('accounting_period_id', $accountingPeriod->accounting_period_id)->where('transaction_type',5);
           
            if( $validated['accountList']==="mainAccount")
            {
                $query->where('Customer_id', $sub_accountT4);
                $querys->where('Customer_id', $sub_accountT4);
            }
            $product_id=$validated['DisplayMethod']==="SelectedProduct"?$validated['product_id']:$uniqueProduct->product_id;
            $query->where('product_id', $product_id);
            $querys->where('product_id', $product_id);
           
            $productName = Product::where('product_id', $product_id)->value('product_name');
            $totalsale = $query->get();
            $balancesSales = $query->get();
         
             // تجميع المبيعات حسب الشهر
             
             $monthlySales = $totalsale->groupBy(function($sale) {
                 return $sale->created_at->format('m'); // تجميع المبيعات حسب السنة والشهر
             });
            

                 $dailyTotals = $monthlySales->map(function($sales, $month) {
                    return [
                        'month' => $month,
                        'total' => $sales->sum('total_Profit')-$sales->sum('discount'), // أو أي عمود يمثل قيمة المبيعات
                    ];
                })->values();
             $monthlySale = $balancesSales->groupBy(function($sal) {
                return $sal->created_at->format('y'); // تجميع المبيعات حسب السنة والشهر
                // تجميع المبيعات حسب السنة والشهر
             });
            

                 $dailyTotal= $monthlySale->map(function($salee, $months) {
                    return [
                        'months' => $months,
                        'totals' => $salee->sum('total_Profit')-$salee->sum('discount'), // أو أي عمود يمثل قيمة المبيعات
                    ];
                })->values();

             // تحويل المجموعة إلى مصفوفة مع إجمالي المبيعات لكل شهر
           
           
        $dataProducts[]=
        [
            'salesProfit4'=>$query->sum('total_profit'),
            'salesDiscount4'=>$query->sum('discount'),
            'salesProfit5'=>$querys->sum('total_profit'),
            'salesDiscount5'=>$querys->sum('discount'),
            'productName'=>$productName,
           
            'dailyTotals'=>$dailyTotals ,
            'dailyTotal'=>$dailyTotal ,




        ];

        }

       
            // الحصول على جميع المبيعات الخاصة بالفترة المحاسبية
           
    
        // dd($dataProducts);
        // dd($salesDiscount);


    // $saleQuantity4 = Sale::where('product_id', $product_id)
    //     ->where('accounting_period_id', $accountingPeriod->accounting_period_id)
    //     ->where('transaction_type', 4)
    //     ->sum('quantity');
        // $Products=Product::all();
        return view('salesReport.print',compact('dataProducts'));

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
