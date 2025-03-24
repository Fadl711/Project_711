<?php

namespace App\Http\Controllers\LocksFinancialPeriods;

use App\Http\Controllers\Controller;
use App\Models\AccountingPeriod;
use App\Models\DailyEntrie;
use App\Models\GeneralEntrie;
use App\Models\GeneralJournal;
use App\Models\Inventory;
use App\Models\MainAccount;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\SubAccount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocksFinancialPeriodsController extends Controller
{
    public function index()
    {
        $accountingPeriodOpen = AccountingPeriod::where('is_closed', false)->first();
        $id = $accountingPeriodOpen->accounting_period_id;
        // التحقق من وجود فترة محاسبية مفتوحة
        if (!$id) {
            return redirect()->back()->with('error', 'لا توجد فترة محاسبية مفتوحة.');
        }

        // عرض البيانات في العرض

        $credit = "credit";
        $debit = "debit";
        $subAccounts = MainAccount::where('AccountClass', 3)->first();
        // صافي الإيرادات---------------------------------------------
        $RevenueDebit = GeneralEntrie::where('typeAccount', 5)
            ->where('accounting_period_id', $id)
            ->where('entry_type', $debit)
            ->where('main_id', '!=', $subAccounts->main_account_id)
            ->sum('amount');
        $RevenueCredit = GeneralEntrie::where('typeAccount', 5)
            ->where('accounting_period_id', $id)
            ->where('entry_type', $credit)
            ->where('main_id', '!=', $subAccounts->main_account_id)
            ->sum('amount');
        $Revenue = $RevenueDebit - $RevenueCredit;
        //---------------------------------------------------------

        // اجمالي  البيع -------------------------------
        $RevenueSales = GeneralEntrie::where('entry_type', $credit)
            ->where('main_id', $subAccounts->main_account_id)
            ->where('accounting_period_id', $id)
            ->sum('amount');
        //---------------------------------------------------------
        // dd($RevenueSales);
        // اجمالي  اشراء-------------------------------
        $RevenuePurchase = GeneralEntrie::where('entry_type', $debit)
            ->where('accounting_period_id', $id)
            ->where('main_id', $subAccounts->main_account_id)
            ->sum('amount');

        //---------------------------------------------------------

        // اجمالي  تكلفة بضاعة نهاية المدة  التي تم جردها------
        $QuantityInventory = Inventory::where('accounting_period_id', $id)
            ->sum('TotalCost');
        //---------------------------------------------------------

        // صافي المصروفات----------------------------------------
        $ExpensesDebit = GeneralEntrie::where('typeAccount', 4)
            ->where('accounting_period_id', $id)
            ->where('entry_type', $debit)
            ->where('main_id', '!=', $subAccounts->main_account_id)
            ->sum('amount');
        $ExpensesCredit = GeneralEntrie::where('typeAccount', 4)
            ->where('accounting_period_id', $id)
            ->where('entry_type', $credit)
            ->where('main_id', '!=', $subAccounts->main_account_id)
            ->sum('amount');
        //---------------------------------------------------------
        $totalRevenue = $Revenue + $RevenueSales + ($QuantityInventory ?? 0); //اجمالي الإيرادات

        $totalExpenses = $ExpensesDebit + $RevenuePurchase - $ExpensesCredit; //اجمالي المصروفات
        $profit = $totalRevenue-$totalExpenses  ;
        // dd($profit);
       return view('locks_financial_period.index', [
            'profit' => $profit, 
            'totalRevenue' => $totalRevenue, 
            'totalExpenses' => $totalExpenses, 

        'accountingPeriodOpen' => $accountingPeriodOpen]);
    }

    public function getProfitAndLossData(Request $request, $id)
    {
        
        $accountingPeriodClose = AccountingPeriod::findOrFail($id);
        $query= GeneralEntrie::where('accounting_period_id', $id);
        $queryGet=$query->get();
      
        try {
            if (!$accountingPeriodClose->is_closed) {
                $accountingPeriodClose->update([
                    'is_closed' => true,
                    'end_date' => now(),
                ]);
            }
            // إنشاء فترة محاسبية جديدة إذا لزم الأمر
            if (!AccountingPeriod::where('is_closed', false)->exists()) {
                AccountingPeriod::create([
                    'Year' => now()->year,
                    'Month' => now()->month,
                    'Today' => now()->format('Y-m-d'),
                    'start_date' => now(),
                    'is_closed' => false,
                ]);
            }
            // معالجة الحسابات الفرعية
            $subAccounts = SubAccount::whereIn('type_account', [1, 2, 3])->get();
            $accountingPeriod = AccountingPeriod::where('is_closed', false)->firstOrFail();

            GeneralJournal::create(['accounting_period_id' => $accountingPeriod->accounting_period_id]);
            $dailyPage = GeneralJournal::latest()->first();
        

            foreach ($subAccounts as $subAccount) {
                // حساب المبالغ المدين والدائن
                $amountDebit = $queryGet->where('sub_id', $subAccount->sub_account_id)->where('entry_type', 'debit')->where('accounting_period_id', $id)->where('currency_name','ريال.يمني')->sum('amount');
                $amountCredit = $queryGet->where('sub_id', $subAccount->sub_account_id)->where('entry_type', 'credit')->where('accounting_period_id', $id)->where('currency_name','ريال.يمني')->sum('amount');

                $debits_SAD = $queryGet->where('sub_id', $subAccount->sub_account_id)->where('entry_type', 'debit')->where('accounting_period_id', $id)->where('currency_name','ريال سعودي')->sum('amount');
                $credits_SAD = $queryGet->where('sub_id', $subAccount->sub_account_id)->where('entry_type', 'credit')->where('accounting_period_id', $id)->where('currency_name','ريال سعودي')->sum('amount');

                $debitd_USD = $queryGet->where('sub_id', $subAccount->sub_account_id)->where('entry_type', 'debit')->where('accounting_period_id', $id)->where('currency_name','دولار امريكي')->sum('amount');
                $credits_USD = $queryGet->where('sub_id', $subAccount->sub_account_id)->where('entry_type', 'credit')->where('accounting_period_id', $id)->where('currency_name','دولار امريكي')->sum('amount');
                $sub = $amountDebit - $amountCredit;
                $subSAD = $debits_SAD - $credits_SAD;
                $subUSD = $debitd_USD - $credits_USD;

                // إذا كان هناك رصيد، قم بإضافة إدخال يومي
                if ($sub != 0) 
                {
                    DailyEntrie::create([
                        'amount_debit' =>max(0, -$sub) ,
                        'account_debit_id' => $subAccount->sub_account_id,
                        'amount_credit' =>max(0, $sub) ,
                        'account_credit_id' => $subAccount->sub_account_id,
                        'statement' => 'رصيد باقي/ يتم اقفال الحساب  وترحيل المبلغ المتبقي لسنة الجديدة ',
                        'daily_page_id' => $dailyPage->page_id,
                        'currency_name' => 'ريال.يمني',
                        'user_id' => auth()->id(),
                        'invoice_type' => 1,
                        'accounting_period_id' => $id,
                        'invoice_id' => $subAccount->sub_account_id,
                        'daily_entries_type' => 'رصيد مرحل',
                    
                    ]);
                    
                    DailyEntrie::create([
                        'amount_debit' => max(0, $sub),
                        'account_debit_id' => $subAccount->sub_account_id,
                        'amount_credit' => max(0, -$sub),
                        'account_credit_id' => $subAccount->sub_account_id,
                        'statement' => 'رصيد افتتاحي',
                        'daily_page_id' => $dailyPage->page_id,
                        'currency_name' => 'ريال.يمني',
                        'user_id' => auth()->id(),
                        'invoice_type' => 1,
                        'accounting_period_id' => $accountingPeriod->accounting_period_id,
                        'invoice_id' => $subAccount->sub_account_id,
                        'daily_entries_type' => 'رصيد افتتاحي',
                        'status_debit' => 'غير مرحل',
                        'status' => 'غير مرحل',
                    ]);
                 
                }
                if ($subSAD != 0) 
                {
                    DailyEntrie::create([
                        'amount_debit' =>max(0, -$subSAD) ,
                        'account_debit_id' => $subAccount->sub_account_id,
                        'amount_credit' =>max(0, $subSAD) ,
                        'account_credit_id' => $subAccount->sub_account_id,
                        'statement' => 'رصيد باقي/ يتم اقفال الحساب  وترحيل المبلغ المتبقي لسنة الجديدة ',
                        'daily_page_id' => $dailyPage->page_id,
                        'currency_name' => 'ريال سعودي',
                        'user_id' => auth()->id(),
                        'invoice_type' => 1,
                        'accounting_period_id' => $id,
                        'invoice_id' => $subAccount->sub_account_id,
                        'daily_entries_type' => 'رصيد مرحل',
                    
                    ]);
                    
                    DailyEntrie::create([
                        'amount_debit' => max(0, $subSAD),
                        'account_debit_id' => $subAccount->sub_account_id,
                        'amount_credit' => max(0, -$subSAD),
                        'account_credit_id' => $subAccount->sub_account_id,
                        'statement' => 'رصيد افتتاحي',
                        'daily_page_id' => $dailyPage->page_id,
                        'currency_name' => 'ريال سعودي',
                        'user_id' => auth()->id(),
                        'invoice_type' => 1,
                        'accounting_period_id' => $accountingPeriod->accounting_period_id,
                        'invoice_id' => $subAccount->sub_account_id,
                        'daily_entries_type' => 'رصيد افتتاحي',
                        'status_debit' => 'غير مرحل',
                        'status' => 'غير مرحل',
                    ]);
                 
                }
                if ($subUSD != 0) 
                {
                    DailyEntrie::create([
                        'amount_debit' =>max(0, -$subUSD) ,
                        'account_debit_id' => $subAccount->sub_account_id,
                        'amount_credit' =>max(0, $subUSD) ,
                        'account_credit_id' => $subAccount->sub_account_id,
                        'statement' => 'رصيد باقي/ يتم اقفال الحساب  وترحيل المبلغ المتبقي لسنة الجديدة ',
                        'daily_page_id' => $dailyPage->page_id,
                        'currency_name' => 'دولار امريكي',
                        'user_id' => auth()->id(),
                        'invoice_type' => 1,
                        'accounting_period_id' => $id,
                        'invoice_id' => $subAccount->sub_account_id,
                        'daily_entries_type' => 'رصيد مرحل',
                    
                    ]);
                    
                    DailyEntrie::create([
                        'amount_debit' => max(0, $subUSD),
                        'account_debit_id' => $subAccount->sub_account_id,
                        'amount_credit' => max(0, -$subUSD),
                        'account_credit_id' => $subAccount->sub_account_id,
                        'statement' => 'رصيد افتتاحي',
                        'daily_page_id' => $dailyPage->page_id,
                        'currency_name' => 'دولار امريكي',
                        'user_id' => auth()->id(),
                        'invoice_type' => 1,
                        'accounting_period_id' => $accountingPeriod->accounting_period_id,
                        'invoice_id' => $subAccount->sub_account_id,
                        'daily_entries_type' => 'رصيد افتتاحي',
                        'status_debit' => 'غير مرحل',
                        'status' => 'غير مرحل',
                    ]);
                 
                }
            }
    
            $this->ProductTransfer($id);
            return response()->json([
            'success' => true,
            'message'=>' إقفال السنة ',

        ]);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage(),
            ]);
        }
    } 
       public function ProductTransfer($id)
    {
        // تعيين معرّف الفترة المحاسبية
        $accountingPeriod = AccountingPeriod::where('accounting_period_id', $id)->firstOrFail();
        $accountingPeriodOprn = AccountingPeriod::where('is_closed', false)->firstOrFail();
        $warehouses = SubAccount::where('account_class', 3)->get();

        foreach ($warehouses as $warehouse) {
            $warehouse_to_id = $warehouse->sub_account_id;

            // // جلب المنتجات المرتبطة بالمخزن
            // $warehousesReturns = Purchase::where('accounting_period_id', $id)
            //     ->where('warehouse_to_id', $warehouse_to_id)
            //     ->whereIn('transaction_type', [1, 6, 3,7])
            //     ->select('product_id') // اختيار الأعمدة المطلوبة
            //     ->distinct() // التأكد من جلب القيم المميزة
            //     ->get(); //

                    $warehousesReturns = Product::all();

            // جلب الكمية من المخزن


            if ($warehousesReturns) {
                foreach ($warehousesReturns as $product) {
                    $productName = Product::where('product_id',$product->product_id)->first();

                    $QuantityInventory = Inventory::where('StoreId', $warehouse_to_id)
                        ->where('accounting_period_id', $id)
                        ->where('product_id', $productName->product_id)
                        ->sum('Quantityprice');
                    // حساب كميات المشتريات والمرتجعات


                    // تحديد الكمية المستخدمة
                    // $Quantity = $QuantityInventory >0 ? $QuantityInventory : ($productPurchase ?? 0);
                    if ($QuantityInventory) {
                        $this->updateOrCreatePurchase($warehouse_to_id, $QuantityInventory, $accountingPeriodOprn->accounting_period_id, $productName->product_id);
                    } elseif ($productName->product_id) 
                    {
                        $this->calculateTotalQuantities($productName->product_id, $warehouse_to_id, $id);
                    }
                }
            }
        }
        return response()->json([
            'success' => true,
            'message'=>'تم إقفال السنة بنجاح',

        ]);
    }

    private function calculateTotalQuantities($product_id, $warehouse_to_id, $id)
    {
        // استعلام لجمع كميات المشتريات
        // $productName = Product::where('product_id',$productid)->first();
        $productid = Product::where('product_id', $product_id)->value('product_id');

        if ($productid) {
         // حساب الكميات بناءً على نوع المعاملة والمخزن
  $purchaseToQuantity = Purchase::where('product_id', $productid)
  ->where('accounting_period_id', $id)
  ->where('warehouse_to_id', $warehouse_to_id)
  ->whereIn('transaction_type', [1, 6, 3,7])
  ->sum('quantity');

  $warehouseFromQuantity = Purchase::where('product_id', $productid)
      ->where('warehouse_from_id', $warehouse_to_id)
      ->where('accounting_period_id', $id)
      ->where('transaction_type', 2)
      ->sum('quantity');
     
  $warehouseFromQuantity3 = Purchase::where('product_id', $productid)
      ->where('warehouse_from_id', $warehouse_to_id)
      ->where('accounting_period_id', $id)
      ->where('transaction_type', 3)
      ->sum('quantity');

  $saleQuantity5 = Sale::where('product_id', $productid)
      ->where('warehouse_to_id', $warehouse_to_id)
      ->where('accounting_period_id', $id)
      ->where('transaction_type', 5)
      ->sum('quantity');

  $saleQuantity4 = Sale::where('product_id', $productid)
      ->where('warehouse_to_id', $warehouse_to_id)
      ->where('accounting_period_id', $id)
      ->where('transaction_type', 4)
      ->sum('quantity');
     

  $productPurchase =( $purchaseToQuantity+$saleQuantity5 )- $warehouseFromQuantity - $warehouseFromQuantity3- $saleQuantity4 ;
            $accountingPeriodOprn = AccountingPeriod::where('is_closed', false)->firstOrFail();

            $this->updateOrCreatePurchase($warehouse_to_id, $productPurchase, $accountingPeriodOprn->accounting_period_id, $productid);
        }
    }

    private function updateOrCreatePurchase($warehouse_to_id, $Quantity, $accountingPeriodId, $productid)
    {
        $productName = Product::where('product_id', $productid)->first();
        $accountingPeriodOprn = AccountingPeriod::where('is_closed', false)->firstOrFail();

        Purchase::create(
            [
                'accounting_period_id' => $accountingPeriodOprn->accounting_period_id,
                'Purchase_invoice_id' => null,
                'Product_name' => $productName->product_name,
                'Barcode' => $productName->Barcode ?? 0,
                'quantity' => $Quantity,
                'Quantityprice' => $Quantity,
                'Purchase_price' => $productName->Purchase_price,
                'Selling_price' => $productName->Selling_price,
                'Total' => $Quantity * $productName->Purchase_price,
                'Cost' => 0,
                'Currency_id' => null,
                'Supplier_id' => null,
                'User_id' => auth()->id(),
                'warehouse_to_id' => $warehouse_to_id,
                'warehouse_from_id' => null,
                'Discount_earned' => 0,
                'Profit' => 0,
                'Exchange_rate' => 1.0,
                'note' => 'hhj',
                'product_id' => $productid,
                'account_id' => $warehouse_to_id,
                'transaction_type' => 7,
                'categorie_id' => null,
            ]
        );

        Product::where('product_id',  $productName->product_id)->update([
            'Quantity' => $Quantity,
        ]);
    }
}
