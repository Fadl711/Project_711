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
            ->where('Main_id', '!=', $subAccounts->main_account_id)
            ->sum('amount');
        $RevenueCredit = GeneralEntrie::where('typeAccount', 5)
            ->where('accounting_period_id', $id)
            ->where('entry_type', $credit)
            ->where('Main_id', '!=', $subAccounts->main_account_id)
            ->sum('amount');
        $Revenue = $RevenueDebit - $RevenueCredit;
        //---------------------------------------------------------

        // اجمالي  البيع -------------------------------
        $RevenueSales = GeneralEntrie::where('entry_type', $credit)
            ->where('Main_id', $subAccounts->main_account_id)
            ->where('accounting_period_id', $id)
            ->sum('amount');
        //---------------------------------------------------------
        // dd($RevenueSales);
        // اجمالي  اشراء-------------------------------
        $RevenuePurchase = GeneralEntrie::where('entry_type', $debit)
            ->where('accounting_period_id', $id)
            ->where('Main_id', $subAccounts->main_account_id)
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
            ->where('Main_id', '!=', $subAccounts->main_account_id)
            ->sum('amount');
        $ExpensesCredit = GeneralEntrie::where('typeAccount', 4)
            ->where('accounting_period_id', $id)
            ->where('entry_type', $credit)
            ->where('Main_id', '!=', $subAccounts->main_account_id)
            ->sum('amount');
        //---------------------------------------------------------
        $totalRevenue = $Revenue + $RevenueSales + ($QuantityInventory ?? 0); //اجمالي الإيرادات

        $totalExpenses = $ExpensesDebit + $RevenuePurchase - $ExpensesCredit; //اجمالي المصروفات
        $profit = $totalExpenses - $totalRevenue;



        // dd($profit);



        return view('locks_financial_period.index', ['profit' => $profit, 'accountingPeriodOpen' => $accountingPeriodOpen]);
    }

    public function getProfitAndLossData(Request $request, $id)
    {

        $accountingPeriodClose = AccountingPeriod::where('accounting_period_id', $id)->first();
        try {

            if ($accountingPeriodClose->is_closed == false) {
                AccountingPeriod::where('accounting_period_id', $id)
                    ->update([
                        'is_closed' => true,
                        'end_date' => now()->format('Y-m-d'),
                    ]);
            }

            if (!AccountingPeriod::where('is_closed', false)->exists()) {
                // إنشاء فترة محاسبية جديدة
                AccountingPeriod::create([
                    'Year' => now()->year,          // إدخال السنة كرقم
                    'Month' => now()->month,        // إدخال الشهر كرقم
                    'Today' => now()->format('Y-m-d'), // إدخال التاريخ الكامل
                    'start_date' => now()->format('Y-m-d'), // إدخال تاريخ البداية
                    'is_closed' => false,           // تحديد أن الفترة غير مغلقة
                ]);
            }

            $subAccounts = SubAccount::whereIn('typeAccount', [1, 2, 3])->get();
            $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();
            $today = Carbon::now()->toDateString();

            GeneralJournal::create([
                'accounting_period_id' => $accountingPeriod->accounting_period_id,
            ]);
            $dailyPage = GeneralJournal::whereDate('created_at', $today)->latest()->first();

            foreach ($subAccounts as $subAccount) {
                $entryTypeDebit = "debit";
                $entryTypeCredit = "credit";
                // جلب المبالغ المدين والدائن من GeneralEntrie للفترة المحاسبية الحالية
                $amountDebit = GeneralEntrie::where('sub_id', $subAccount->sub_account_id)
                    ->where('entry_type', $entryTypeDebit)
                    ->where('accounting_period_id', $id)
                    ->sum('amount');
                $amountCredit = GeneralEntrie::where('sub_id', $subAccount->sub_account_id)
                    ->where('entry_type', $entryTypeCredit)
                    ->where('accounting_period_id', $id)
                    ->sum('amount');
                // حساب الفرق وتحديث الرصيد الافتتاحي بناءً عليه
                $sub = $amountDebit - $amountCredit;
                if ($sub == 0) {
                    $subAccount->update([
                        'creditor_amount' => 0,
                        'debtor_amount' => 0,
                    ]);
                }
                if ($sub > 0) {
                    $debtor_amount = $sub;
                    $creditor_amount = 0;
                    $subAccount->update([
                        'debtor_amount' => $sub,
                        'creditor_amount' => 0,
                    ]);
                }
                if ($sub < 0) {
                    $debtor_amount = 0;
                    $creditor_amount = abs($sub);
                    $subAccount->update([
                        'creditor_amount' => abs($sub),
                        'debtor_amount' => 0,
                    ]);
                }

                if ($sub !== 0) {
                    $dailyEntry = DailyEntrie::create([
                        'Amount_debit' => $debtor_amount  ?: 0,
                        'account_debit_id' => $subAccount->sub_account_id,
                        'Amount_Credit' => $creditor_amount  ?: 0,
                        'account_Credit_id' => $subAccount->sub_account_id,
                        'Statement' => ' رصيد افتتاحي  ',
                        'Daily_page_id' => $dailyPage->page_id,
                        'Currency_name' => 'Y',
                        'User_id' => auth()->id(),
                        'Invoice_type' => 1,
                        'accounting_period_id' => $accountingPeriod->accounting_period_id,
                        'Invoice_id' =>  $subAccount->sub_account_id,
                        'daily_entries_type' => 'رصيد افتتاحي',
                        'status_debit' => 'غير مرحل',
                        'status' => 'غير مرحل',
                    ]);
                }
            }

            $credit = "credit";
            $debit = "debit";
            $subAccounts = MainAccount::where('AccountClass', 3)->first();
            // صافي الإيرادات---------------------------------------------
            $RevenueDebit = GeneralEntrie::where('typeAccount', 5)
                ->where('accounting_period_id', $id)
                ->where('entry_type', $debit)
                ->where('Main_id', '!=', $subAccounts->main_account_id)
                ->sum('amount');
            $RevenueCredit = GeneralEntrie::where('typeAccount', 5)
                ->where('accounting_period_id', $id)
                ->where('entry_type', $credit)
                ->where('Main_id', '!=', $subAccounts->main_account_id)
                ->sum('amount');
            $Revenue = $RevenueDebit - $RevenueCredit;
            //---------------------------------------------------------

            // اجمالي  البيع -------------------------------
            $RevenueSales = GeneralEntrie::where('entry_type', $credit)
                ->where('Main_id', $subAccounts->main_account_id)
                ->where('accounting_period_id', $id)
                ->sum('amount');
            //---------------------------------------------------------
            // dd($RevenueSales);
            // اجمالي  اشراء-------------------------------
            $RevenuePurchase = GeneralEntrie::where('entry_type', $debit)
                ->where('accounting_period_id', $id)
                ->where('Main_id', $subAccounts->main_account_id)
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
                ->where('Main_id', '!=', $subAccounts->main_account_id)
                ->sum('amount');
            $ExpensesCredit = GeneralEntrie::where('typeAccount', 4)
                ->where('accounting_period_id', $id)
                ->where('entry_type', $credit)
                ->where('Main_id', '!=', $subAccounts->main_account_id)
                ->sum('amount');
            //---------------------------------------------------------
            $totalRevenue = $Revenue + $RevenueSales + $QuantityInventory ; //اجمالي الإيرادات

            $totalExpenses = $ExpensesDebit + $RevenuePurchase - $ExpensesCredit; //اجمالي المصروفات
            $profit =$totalRevenue - $totalExpenses ;


            if ($profit > 0) {
                $profit = $totalRevenue - $totalExpenses;
                $account_debit_id = 10;
                $account_credit_id = 9;
                $comment = "إثبات إقفال مجمل الربح ";
            }

            if ($profit < 0) {
                $profit = $totalExpenses - $totalRevenue;

                $account_debit_id = 9;
                $account_credit_id = 10;
                $comment = "إثبات إقفال مجمل الخسارة ";
            }
            if ($profit) {
                $dailyEntry = DailyEntrie::create([
                    'Amount_debit' =>abs( $profit ) ?: 0,
                    'account_debit_id' => $account_debit_id,
                    'Amount_Credit' => abs( $profit )   ?: 0,
                    'account_Credit_id' => $account_credit_id,
                    'Statement' => $comment,
                    'Daily_page_id' => $dailyPage->page_id,
                    'Currency_name' => 'ر',
                    'User_id' => auth()->id(),
                    'Invoice_type' => 1,
                    'accounting_period_id' => $accountingPeriod->accounting_period_id,
                    'Invoice_id' => null,
                    'daily_entries_type' => 'رصيد افتتاحي',
                    'status_debit' => 'غير مرحل',
                    'status' => 'غير مرحل',
                ]);
            }
            $this->ProductTransfer($id);

            return response()->json([

                'assets' => 0,
                'liabilities' => 0,
                'id' => $id,
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء الحذف: ' . $e->getMessage()
            ]);
        }
    }
    public function ProductTransfer($id)
    {
        // $id=1;
        // تعيين معرّف الفترة المحاسبية
        $accountingPeriod = AccountingPeriod::where('accounting_period_id', $id)->firstOrFail();
        $accountingPeriodOprn = AccountingPeriod::where('is_closed', false)->firstOrFail();
        $warehouses = SubAccount::where('AccountClass', 3)->get();

        foreach ($warehouses as $warehouse) {
            $warehouse_to_id = $warehouse->sub_account_id;

            // جلب المنتجات المرتبطة بالمخزن
            $warehousesReturns = Purchase::where('accounting_period_id', $id)
                ->where('warehouse_to_id', $warehouse_to_id)
                ->whereIn('transaction_type', [1, 6, 3, 7])
                ->select('product_id', 'Quantityprice') // اختيار الأعمدة المطلوبة
                ->distinct() // التأكد من جلب القيم المميزة
                ->get(); //

            // جلب الكمية من المخزن


            if ($warehousesReturns) {
                foreach ($warehousesReturns as $product) {
                    $QuantityInventory = Inventory::where('StoreId', $warehouse_to_id)
                        ->where('accounting_period_id', $id)
                        ->where('product_id', $product->product_id)
                        ->sum('Quantityprice');
                    // حساب كميات المشتريات والمرتجعات


                    // تحديد الكمية المستخدمة
                    // $Quantity = $QuantityInventory >0 ? $QuantityInventory : ($productPurchase ?? 0);
                    if ($QuantityInventory) {
                        $this->updateOrCreatePurchase($warehouse_to_id, $QuantityInventory, $accountingPeriodOprn->accounting_period_id, $product->product_id);
                    } elseif ($product->product_id) {
                        $this->calculateTotalQuantities($product->product_id, $warehouse_to_id, $id);
                    }
                }
            }
        }
    }

    private function calculateTotalQuantities($product_id, $warehouse_to_id, $id)
    {
        // استعلام لجمع كميات المشتريات
        if ($product_id) {
            $purchases = Purchase::select('product_id', 'warehouse_to_id', DB::raw('SUM(quantity) as total_quantity'))
                ->where('product_id', $product_id)
                ->where('warehouse_to_id', $warehouse_to_id)
                ->where('accounting_period_id', $id)
                ->whereIn('transaction_type', [1, 6, 3, 7])
                ->groupBy('product_id', 'warehouse_to_id');


            // استعلام لجمع كميات المرتجعات من المشتريات
            $purchasesReturn = Purchase::select('product_id', 'warehouse_to_id', DB::raw('SUM(quantity) as totalquantity'))
                ->where('product_id', $product_id)
                ->where('warehouse_to_id', $warehouse_to_id)
                ->where('accounting_period_id', $id)
                ->where('transaction_type', 2)
                ->groupBy('product_id', 'warehouse_to_id');

            // جمع الكمية من المخزن المحدد
            $warehouseFromQuantity3 = Purchase::where('product_id', $product_id)
                ->where('warehouse_from_id', $warehouse_to_id)
                ->where('accounting_period_id', $id)
                ->where('transaction_type', 3)
                ->sum('quantity');
            // استعلام لجمع كميات المرتجعات من المبيعات
            $saleReturn = Sale::select('product_id', 'warehouse_to_id', DB::raw('SUM(quantity) as total_quantity'))
                ->where('product_id', $product_id)
                ->where('warehouse_to_id', $warehouse_to_id)
                ->where('accounting_period_id', $id)
                ->where('transaction_type', 5)
                ->groupBy('product_id', 'warehouse_to_id');

            // استعلام لجمع كميات المبيعات
            $sales = Sale::select('product_id', 'warehouse_to_id', DB::raw('SUM(quantity) as totalquantity'))
                ->where('product_id', $product_id)
                ->where('warehouse_to_id', $warehouse_to_id)
                ->where('accounting_period_id', $id)
                ->where('transaction_type', 4)
                ->groupBy('product_id', 'warehouse_to_id');

            // دمج كميات المشتريات مع كميات المرتجعات من المبيعات
            $purchasesSummary = $purchases->union($saleReturn)->get();
            // حساب إجمالي الكميات
            $totalQuantity = $purchasesSummary->sum('total_quantity');

            // دمج كميات المبيعات مع كميات المرتجعات من المشتريات
            $purchasesReturnEndSalesSummary = $sales->union($purchasesReturn)->get();
            $purchasesReturnEndSales = $purchasesReturnEndSalesSummary->sum('totalquantity');
            // حساب الكمية النهائية للمنتج
            $productPurchase = $totalQuantity - $warehouseFromQuantity3 - $purchasesReturnEndSales;
            $accountingPeriodOprn = AccountingPeriod::where('is_closed', false)->firstOrFail();

            $this->updateOrCreatePurchase($warehouse_to_id, $productPurchase, $accountingPeriodOprn->accounting_period_id, $product_id);
        }
    }

    private function updateOrCreatePurchase($warehouse_to_id, $Quantity, $accountingPeriodId, $product_id)
    {
        $productName = Product::where('product_id', $product_id)->first();

        Purchase::create(
            [
                'accounting_period_id' => $accountingPeriodId,
                'Purchase_invoice_id' => $request->Purchase_invoice_id ?? null,

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
                'product_id' => $productName->product_id,
                'account_id' => $warehouse_to_id,
                'transaction_type' => 7,
                'categorie_id' => null,
            ]
        );
        $productName->update([
            'Quantity' => $Quantity,
        ]);
    }
}
