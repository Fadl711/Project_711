<?php

use App\Http\Controllers\CustomerCoctroller;
use App\Http\Controllers\HomeCoctroller;
use App\Http\Controllers\AccountCoctroller;
use App\Http\Controllers\Accounts\main_accounts\MainaccountController;

use App\Http\Controllers\Accounts\Review_BudgetController;
use App\Http\Controllers\Accounts\sub_accounts\SubaccountController;
use App\Http\Controllers\Accounts\TreeAccountController;
use App\Http\Controllers\ArtController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\bondController\BondController;
use App\Http\Controllers\bondController\receipController\All_Receipt_BondController;
use App\Http\Controllers\bondController\receipController\ReceipController;
use App\Http\Controllers\chartController\ChartController;
use App\Http\Controllers\DailyRestrictionController\RestrictionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\DoubleEntryController;
use App\Http\Controllers\EquipmentMaintenanceController;
use App\Http\Controllers\FixedAssetsController;
use App\Http\Controllers\generalEntrieController;
use App\Http\Controllers\GitController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\InventoryTransactionController;
use App\Http\Controllers\InvoicePurchaseController;
use App\Http\Controllers\invoicesController\AllBillsController;
use App\Http\Controllers\LocksFinancialPeriods\LocksFinancialPeriodsController;
use App\Http\Controllers\ManufacturingCostController;
use App\Http\Controllers\OperationController;
use App\Http\Controllers\PaymentCoctroller;
use App\Http\Controllers\ProductBomController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\settingController\SettingController;
use App\Http\Controllers\SupplierCoctroller;
use App\Http\Controllers\productController\CategoryController;
use App\Http\Controllers\productController\ProductCoctroller;
use App\Http\Controllers\ProductionOrderController;
use App\Http\Controllers\ProductionSystem\ProductionLineController;
use App\Http\Controllers\ProductionSystem\ProductionStageController;
use App\Http\Controllers\purchases\PurchaseController;
use App\Http\Controllers\RawMaterialTransactionController;
use App\Http\Controllers\refundsController\purchasesController\Purchase_RefundController;
use App\Http\Controllers\refundsController\salesController\Sale_RefundController;
use App\Http\Controllers\reportsConreoller;
use App\Http\Controllers\RouteClearController;
use App\Http\Controllers\Sale\InvoiceSaleController;
use App\Http\Controllers\SaleCoctroller\SaleController;
use App\Http\Controllers\settingController\company_dataController\Company_DataController;
use App\Http\Controllers\settingController\currenciesController\CurrencieController;
use App\Http\Controllers\settingController\default_customerController;
use App\Http\Controllers\settingController\default_supplierController;

use App\Http\Controllers\settingController\WarehouseController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\SyncController;
use App\Http\Controllers\Transfers\TransferController;
use App\Http\Controllers\UserPermissionController;
use App\Http\Controllers\UsersController\UsersController;

use App\Models\AccountingPeriod;
use App\Models\Currency;
use App\Models\DailyEntrie;
use App\Models\InventoryTransaction;
use App\Models\ManufacturingCost;
use App\Models\PaymentBond;

use App\Models\SubAccount;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use NumberToWords\NumberToWords;

// Route::get('/', function () {
//     return view('welcome');
// });


// أضف middleware الجلسة يدويًا
Route::middleware('web')->group(function () {
    Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('google.login');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);
});


Route::post('/sync', [SyncController::class, 'sync'])->name('sync');
Route::post('/sales/import', [SyncController::class, 'import'])->name('sales.import');
Route::middleware(['auth', 'web'])->group(function () {
    Route::get('/backup/google', [BackupController::class, 'backupDatabase'])->name('backup.google');
});

Route::post('restore', [DatabaseController::class, 'restoreDatabase']);
Route::get('/backup10', [BackupController::class, 'showForm'])->name('backup.form');
Route::post('/backup1', [BackupController::class, 'createBackup'])->name('backup');
Route::middleware(['auth'])->group(function () {
    Route::get('/all-products/{id}/show', [ProductCoctroller::class, 'allProducts'])->name('all-products');
    Route::get('/all-products/{id}/print', [ProductCoctroller::class, 'print'])->name('report.print');

    Route::get('/raw-material-transactions/index', [RawMaterialTransactionController::class, 'index'])->name('raw-material-transactions.index');
    Route::get('/raw-material-transactions/create', [RawMaterialTransactionController::class, 'create'])->name('raw-material-transactions.create');
    Route::post('/raw-material-transactions/store', [RawMaterialTransactionController::class, 'store'])->name('raw-material-transactions.store');
    // routes/web.php
    Route::get('/raw-material-transactions/{id}/show', [RawMaterialTransactionController::class, 'show'])
        ->name('raw-material-transactions.show');
    Route::get('/raw-material-transactions/{id}edit', [RawMaterialTransactionController::class, 'edit'])->name('raw-material-transactions.edit');
    Route::get('/raw-material-transactions/{id}destroy', [RawMaterialTransactionController::class, 'destroy'])->name('raw-material-transactions.destroy');
    // صيانة المعدات
    Route::get('/equipment-maintenance/index', [EquipmentMaintenanceController::class, 'index'])->name('equipment-maintenance.index');
    Route::get('/equipment-maintenance/create', [EquipmentMaintenanceController::class, 'create'])->name('equipment-maintenance.create');
    Route::post('/equipment-maintenance/store', [EquipmentMaintenanceController::class, 'store'])->name('equipment-maintenance.store');

    Route::get('equipment-maintenance/{equipmentMaintenance}', [EquipmentMaintenanceController::class, 'show'])->name('equipment-maintenance.show');
    Route::post('equipment-maintenance/{equipmentMaintenance}/start', [EquipmentMaintenanceController::class, 'startMaintenance'])->name('equipment-maintenance.start');
    Route::post('equipment-maintenance/{equipmentMaintenance}/complete', [EquipmentMaintenanceController::class, 'completeMaintenance'])->name('equipment-maintenance.complete');
    Route::patch('equipment-maintenance/{equipmentMaintenance}/cancel', [EquipmentMaintenanceController::class, 'cancelMaintenance'])->name('equipment-maintenance.cancel');

    Route::get('/production-system/dashboard', [ProductionLineController::class, 'dashboard'])->name('production_system.dashboard');

    Route::get('/production_lines/create', [ProductionLineController::class, 'create'])->name('production_lines.create');
    Route::post('/production_lines/store', [ProductionLineController::class, 'store'])->name('production_lines.store');
    Route::get('/production_lines/{production_line}/show', [ProductionLineController::class, 'show'])->name('production_lines.show');
    Route::get('/production_lines/{production_line}/edit', [ProductionLineController::class, 'edit'])->name('production_lines.edit');
    Route::delete('/production_lines/{id}/destroy', [ProductionLineController::class, 'destroy'])->name('production_lines.destroy');

    Route::get('/manufacturing_costs/index', [ManufacturingCostController::class, 'index'])->name('manufacturing-costs.index');
    Route::get('/manufacturing_costs/create', [ManufacturingCostController::class, 'create'])->name('manufacturing-costs.create');
    Route::post('/manufacturing_costs/store', [ManufacturingCostController::class, 'store'])->name('manufacturing-costs.store');
    Route::get('/manufacturing_costs/{manufacturingCost}/edit', [ManufacturingCostController::class, 'edit'])->name('manufacturing-costs.edit');
    Route::get('/manufacturing_costs/{manufacturingCost}/show', [ManufacturingCostController::class, 'show'])->name('manufacturing-costs.show');
    Route::get('/manufacturing_costs/analysis', [ManufacturingCostController::class, 'costAnalysis'])->name('manufacturing-costs.analysis');



    Route::get('/inventory-transactions/index', [InventoryTransactionController::class, 'create'])->name('inventory-transactions.index');
    // Route::get('/inventory-transactions/create', [InventoryTransactionController::class, 'create'])->name('inventory-transactions.create');
    // Route::post('/inventory-transactions/store', [InventoryTransactionController::class, 'store'])->name('inventory-transactions.store');
    // Route::get('/inventory-transactions/{id}/edit', [InventoryTransactionController::class, 'edit'])->name('inventory-transactions.edit');
    // Route::delete('/inventory-transactions/{id}/destroy', [InventoryTransactionController::class, 'destroy'])->name('inventory-transactions.destroy');
    // Route::get('/inventory-transactions/{id}/show', [InventoryTransactionController::class, 'show'])->name('inventory-transactions.show');

    Route::get('/product-boms/index', [ProductBomController::class, 'index'])->name('product-boms.index');
    Route::get('/product-boms/create', [ProductBomController::class, 'create'])->name('product-boms.create');
    Route::post('/product-boms/store', [ProductBomController::class, 'store'])->name('product-boms.store');
    Route::post('/product-boms/update', [ProductBomController::class, 'update'])->name('product-boms.update');
    Route::get('/product-boms/{id}/show', [ProductBomController::class, 'show'])->name('product-boms.show');
    Route::get('/product-boms/{id}/edit', [ProductBomController::class, 'edit'])->name('product-boms.edit');
    Route::delete('/product-boms/{id}/destroy', [ProductBomController::class, 'destroy'])->name('product-boms.destroy');
    Route::get('/get-product-boms/{id}', [ProductBomController::class, 'getProductBoms'])->name('getProductBoms');



    Route::get('/production-stages/index', [ProductionStageController::class, 'index'])->name('production-stages.index');
    Route::get('/production-stages/create', [ProductionStageController::class, 'create'])->name('production-stages.create');
    Route::post('/production-stages/store', [ProductionStageController::class, 'store'])->name('production-stages.store');
    Route::get('/production-stages/{productionStage}/show', [ProductionStageController::class, 'show'])->name('production-stages.show');
    Route::get('/production-stages/{productionStage}/edit', [ProductionStageController::class, 'edit'])->name('production-stages.edit');
    Route::delete('/production-stages/{id}/destroy', [ProductionStageController::class, 'destroy'])->name('production-stages.destroy');
    Route::put('production-stages/{productionStage}/toggle-status', [ProductionStageController::class, 'toggleStatus'])->name('production-stages.toggle-status');
    // أوامر الإنتاج
    Route::get('/production_orders/index', [ProductionOrderController::class, 'index'])->name('production_orders.index');
    Route::get('/production-orders/create', [ProductionOrderController::class, 'create'])->name('production-orders.create');
    Route::post('/production-orders/store', [ProductionOrderController::class, 'store'])->name('production_orders.store');
    Route::get('/production-orders/{productionOrder}', [ProductionOrderController::class, 'show'])->name('production-orders.show');
    Route::get('/production-orders/{productionOrder}/edit', [ProductionOrderController::class, 'edit'])->name('production-orders.edit');
    Route::put('/production-orders/{productionOrder}', [ProductionOrderController::class, 'update'])->name('production-orders.update');
    Route::delete('/production-orders/{productionOrder}', [ProductionOrderController::class, 'destroy'])->name('production-orders.destroy');
    Route::post('/production-orders/{productionOrder}/change-status', [ProductionOrderController::class, 'changeStatus'])->name('production-orders.change-status');


    Route::get('/products', [ProductCoctroller::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductCoctroller::class, 'create'])->name('products.create');
    Route::post('/products/store', [ProductCoctroller::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductCoctroller::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductCoctroller::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductCoctroller::class, 'destroy'])->name('products.destroy');
    Route::get('/GetProduct/{categoryId}/price', [CategoryController::class, 'getUnitPrice'])->name('price.getUnitPrice');
    Route::post('/products/{product}/update-default-category', [ProductCoctroller::class, 'updateDefaultCategory'])->name('products.update-default-category');
    Route::get('/products/{id}/Category', [CategoryController::class, 'create'])->name('Category.create');
    Route::post('/Category/store', [CategoryController::class, 'store'])->name('Category.store');
    Route::get('/Category/{Category}/edit', [CategoryController::class, 'edit'])->name('Category.edit');
    Route::put('/Category/{Category}', [CategoryController::class, 'update'])->name('Category.update');
    Route::delete('/Category/{Category}', [CategoryController::class, 'destroy'])->name('Category.destroy');

    Route::post('/route-clear', [RouteClearController::class, 'clearRoutes'])->name('route.clear');
    Route::post('/git-pull', [GitController::class, 'gitPull'])->name('git.pull');

    Route::post('/send-sms', [SmsController::class, 'sendSms'])->name('send.sms');
    Route::get('/Default_Supplier', [default_supplierController::class, 'index'])->name('default_suppliers.index');
    Route::get('/Default_Supplier/create', [default_supplierController::class, 'create'])->name('default_suppliers.create');
    Route::post('Default_Supplier/store', [default_supplierController::class, 'store'])->name('default_suppliers.store');
    Route::get('/Default_Supplier/{id}/edit', [default_supplierController::class, 'edit'])->name('default_suppliers.edit');
    Route::put('/Default_Supplier/{id}/update', [default_supplierController::class, 'update'])->name('default_suppliers.update');
    Route::delete('/Default_Supplier/{id}/destroy', [default_supplierController::class, 'destroy'])->name('default_suppliers.destroy');

    Route::get('/Default_customer', [default_customerController::class, 'index'])->name('default_customers.index');
    Route::post('Default_customer/store', [default_customerController::class, 'store'])->name('default_customers.store');
    Route::delete('/Default_customer/{id}/destroy', [default_customerController::class, 'destroy'])->name('default_customers.destroy');
    Route::post('Default_warehouse/store', [default_customerController::class, 'storeWarehouse'])->name('default_warehouse.store');
    Route::delete('/Default_warehouse/{id}/destroy', [default_customerController::class, 'destroyWarehouse'])->name('default_warehouse.destroy');
    Route::post('Default_financial/store', [default_customerController::class, 'storeFinancial'])->name('default_financial.store');
    Route::delete('/Default_financial/{id}/destroy', [default_customerController::class, 'destroyFinancial'])->name('default_financial.destroy');
    Route::get('/customers', [CustomerCoctroller::class, 'index'])->name('customers.index');
    Route::get('/customers/create', [CustomerCoctroller::class, 'create'])->name('customers.create');
    Route::post('/customers/store', [CustomerCoctroller::class, 'store'])->name('customers.store');

    Route::get('/general_entries/show', [generalEntrieController::class, 'show'])->name('general_entries.show');
    Route::get('/customers/show', [CustomerCoctroller::class, 'show'])->name('customers.show');
    Route::get('/customers/{id}/statement', [CustomerCoctroller::class, 'createStatement'])->name('customers.statement');
    Route::get('/report/create', [reportsConreoller::class, 'create'])->name('report.create');

    Route::post('/invoicePurchases/store', [PurchaseController::class, 'store'])->name('invoicePurchases.store');
    Route::get('/api/products/search', [PurchaseController::class, 'search']);
    Route::get('/invoice_purchases/show/{id}', [InvoicePurchaseController::class, 'bills_purchase_show'])->name('bills_purchase_show');
    Route::get('/invoice_purchases/index', [InvoicePurchaseController::class, 'index'])->name('invoice_purchase.index');
    Route::get('/api/purchase-invoices/{filterType}', [InvoicePurchaseController::class, 'getPurchaseInvoices']);
    Route::get('/api/purchase-invoices', [InvoicePurchaseController::class, 'searchInvoices']);
    Route::get('/Purchase', [PurchaseController::class, 'create'])->name('Purchases.create');
    Route::post('/Purchases/storc', [PurchaseController::class, 'storc'])->name('Purchases.storc');
    Route::get('/Purchases/{id}/main-accounts', [PurchaseController::class, 'getMainAccounts'])->name('main-accounts');
    // Route::get('/print-receipt/{id}', [PurchaseController::class, 'printReceipt'])->name('receipt.print');
    Route::post('/invoicePurchases/saveAndPrint', [PurchaseController::class, 'saveAndPrint'])->name('invoicePurchases.saveAndPrint');
    Route::get('/get-purchases-by-invoice', [PurchaseController::class, 'getPurchasesByInvoice'])->name('getPurchasesByInvoice');
    Route::get('/invoice_purchases/{id}/print', [PurchaseController::class, 'print'])->name('invoicePurchases.print');
    Route::get('/invoice_purchases/{id}/GetInvoiceNumber', [InvoicePurchaseController::class, 'GetInvoiceNumber'])->name('GetInvoiceNumber');
    Route::get('/get-purchases-by-invoice/ArrowRight', [InvoicePurchaseController::class, 'getpurchasesByInvoiceArrowRight'])->name('getpurchasesByInvoiceArrowRight');
    Route::get('/get-purchases-by-invoice/ArrowLeft', [InvoicePurchaseController::class, 'getpurchasesByInvoiceArrowLeft'])->name('getpurchasesByInvoiceArrowLeft');
    Route::get('/purchases/{id}', [PurchaseController::class, 'edit'])->name('purchases.edit');
    Route::delete('/purchases/{id}', [PurchaseController::class, 'destroy'])->name('purchases.destroy');
    // Route::delete('/purchases/destroyInvoice/{id}',[PurchaseController::class,'destroyInvoice'])->name('purchases.destroyInvoice');
    Route::delete('/purchase-invoices/{id}', [InvoicePurchaseController::class, 'deleteInvoice'])->name('purchase-invoice.delete');

    Route::get('/balancing', [AccountCoctroller::class, 'balancing'])->name('accounts.balancing');
    Route::post('/invoiceSales/store', [InvoiceSaleController::class, 'store'])->name('invoiceSales.store');
    Route::post('/invoiceSales/update', [InvoiceSaleController::class, 'update'])->name('invoiceSales.update');

    Route::post('/Saleupdate', [SaleController::class, 'Saleupdate'])->name('Saleupdate');
    Route::get('/invoice_sales', [AllBillsController::class, 'all_invoices_sale'])->name('invoice_sales.all_invoices_sale');
    Route::get('/sales', [SaleController::class, 'create'])->name('sales.create');
    Route::post('/sales/store', [SaleController::class, 'store'])->name('sales.store');
    Route::get('/sales/{id}', [SaleController::class, 'edit'])->name('sales.edit');
    Route::delete('/sales/{id}', [SaleController::class, 'destroy'])->name('sales.destroy');
    Route::delete('/sales-invoices/{id}', [SaleController::class, 'deleteInvoice'])->name('sales-invoice.delete');
    Route::get('/get-sales-by-invoice/ArrowRight', [SaleController::class, 'getSalesByInvoiceArrowRight'])->name('getSalesByInvoiceArrowRight');
    Route::get('/get-sales-by-invoice/ArrowLeft', [SaleController::class, 'getSalesByInvoiceArrowLeft'])->name('getSalesByInvoiceArrowLeft');
    Route::get('/invoiceSales/{id}/print', [InvoiceSaleController::class, 'print'])->name('invoiceSales.print');
    Route::get('/api/sale-invoices/{filterType}', [InvoiceSaleController::class, 'getSaleInvoice']);
    Route::get('/api/sale-invoices', [InvoiceSaleController::class, 'searchInvoices'])->name('searchInvoices');


    Route::get('/all_bills_sale', [AllBillsController::class, 'all_bills_sale'])->name('invoice_sales.all_bills_sale');
    Route::get('/print_bills_sale', [AllBillsController::class, 'print_bills_sale'])->name('print_bills_sale');
    Route::get('/bills_sale_show', [AllBillsController::class, 'bills_sale_show'])->name('invoice_sales.bills_sale_show');

    Route::get('/operations', [OperationController::class, 'index'])->name('operations.index');
    Route::post('/operations/mark-all-seen', [OperationController::class, 'markAllSeen'])->name('operations.markAllSeen');
    Route::post('/operations/{operation}/mark-seen', [OperationController::class, 'markSeen'])->name('operations.markSeen');
    Route::delete('/operations/{operation}', [OperationController::class, 'destroy'])->name('operations.destroy');

    Route::get('/api/Receip-invoices/{filterType}', [ReceipController::class, 'getPaymentBond']);
    Route::get('/api/Receip-invoices', [ReceipController::class, 'searchInvoices']);
    Route::get('/ping', function () {
        DB::select('SELECT 1');
        return response()->json(['status' => 'alive']);
    });

    Route::post('/store/storeUp', function () {
        $accountingPeriod = AccountingPeriod::where('is_closed', false)->first();


        $numberToWords = new NumberToWords();
        $numberTransformer = $numberToWords->getNumberTransformer('ar');
        // 3. جلب جميع العملات والحسابات مرة واحدة
        $currencies = Currency::all()->keyBy('currency_name');
        $subAccounts = SubAccount::all()->keyBy('sub_account_id');

        // 4. معالجة البيانات باستخدام chunk
        $paymentInvoices = [];

        DailyEntrie::where('accounting_period_id', $accountingPeriod->accounting_period_id)
            ->where('daily_entries_type', 'سند صرف')
            ->chunk(50, function ($entries) use (&$paymentInvoices, $numberTransformer, $currencies, $subAccounts) {
                // dd($entries);

                foreach ($entries as $entry) {

                    $Main_debit_account_id = SubAccount::where('sub_account_id', $entry->account_debit_id)->first();
                    $Main_Credit_account_id = SubAccount::where('sub_account_id', $entry->account_credit_id)->first();
                    $currs = Currency::where('currency_name', $entry->currency_name)->first();

                    $paymentBond =  PaymentBond::updateOrCreate(
                        ['payment_bond_id' => $entry->invoice_id],
                        [
                            'accounting_period_id' => $entry->accounting_period_id,
                            'Main_debit_account_id' => $Main_debit_account_id->main_id,
                            'Debit_sub_account_id' => $entry->account_debit_id,
                            'Main_Credit_account_id' =>  $Main_Credit_account_id->main_id,
                            'Credit_sub_account_id' => $entry->account_credit_id,
                            'payment_type' => $entry->invoice_type,
                            'Currency_id' => $currs->currency_id ?? null,
                            'exchange_rate' => $entry->exchange_rate ?? 1,
                            'Amount_debit' => $entry->amount_credit,
                            'transaction_type' => $entry->daily_entries_type,
                            'Statement' => $entry->statement,
                            'User_id' => $entry->user_id,
                            'created_at' => $entry->created_at,
                            'updated_at' => $entry->updated_at,
                        ]
                    );
                }
            });

        return response()->json(['PaymentInvoices' => $paymentInvoices], 200);
    });

    Route::get('/bonds', [BondController::class, 'bonds'])->name('bonds.index');
    Route::get('/Receip/create', [ReceipController::class, 'create'])->name('Receip.create');
    Route::post('/Receip', [ReceipController::class, 'store'])->name('Receip.store');
    Route::get('/Receips/{id}/print', [ReceipController::class, 'print'])->name('receip.print');
    Route::get('/Receip/show_all_receipt', [All_Receipt_BondController::class, 'show_all_receipt'])->name('show_all_receipt');
    Route::get('/Receips/{Receip}', [ReceipController::class, 'show'])->name('receip.show');
    Route::get('/Receip/{id}/edit', [ReceipController::class, 'edit'])->name('receip.edit');
    Route::put('/Receip/update', [ReceipController::class, 'update'])->name('receip.update');
    Route::delete('/Receips/{id}/destroy', [ReceipController::class, 'destroy'])->name('receip_destroy.destroy');
    Route::post('/Receip/stor', [ReceipController::class, 'stor'])->name('Receip.stor');

    // Route::get('/exchange/index', [ExchangeController::class, 'index'])->name('exchange.index');
    // Route::post('/exchange', [ExchangeController::class, 'store'])->name('exchange.store');
    // Route::get('/exchange/all_exchange_bonds', [ExchangeController::class, 'all_exchange_bonds'])->name('all_exchange_bonds');
    // Route::get('/exchanges/{exchange}', [ExchangeController::class, 'show'])->name('exchange.show');
    // Route::get('/exchange/{id}/print', [ExchangeController::class, 'print'])->name('exchange.print');
    // Route::get('/exchange/{id}/edit', [ExchangeController::class, 'edit'])->name('exchange.edit');
    // Route::put('/exchange/update', [ExchangeController::class, 'update'])->name('exchange.update');
    // Route::delete('/exchanges/{exchange}', [ExchangeController::class, 'destroy'])->name('exchange.destroy');
    // Route::post('/exchange/stor', [ReceipController::class, 'stor'])->name('exchange.stor');

    Route::get('/restrictions/index', [RestrictionController::class, 'index'])->name('restrictions.index');
    Route::get('/restrictions/create', [RestrictionController::class, 'create'])->name('restrictions.create');
    Route::get('/restrictions/create/Currency', [RestrictionController::class, 'createCurrency'])->name('restrictions.createCurrency');
    Route::get('/restrictions/all_restrictions_show/{id}', [RestrictionController::class, 'all_restrictions_show'])->name('all_restrictions_show');
    Route::get('/restrictions/pages', [RestrictionController::class, 'pages'])->name('restrictions.pages');
    Route::get('/restrictions/{id}/edit', [RestrictionController::class, 'edit'])->name('restrictions.edit');
    Route::get('/restrictions/{id}', [RestrictionController::class, 'show'])->name('restrictions.show');
    Route::get('/restrictions/{id}/print', [RestrictionController::class, 'print'])->name('restrictions.print');
    Route::post('/daily_restrictions/store', [RestrictionController::class, 'store'])->name('daily_restrictions.store');
    Route::post('/daily_restrictions/storeCurrency', [RestrictionController::class, 'storeCurrency'])->name('daily_restrictions.storeCurrency');
    Route::post('/daily_restrictions/saveAndPrint', [RestrictionController::class, 'saveAndPrint'])->name('daily_restrictions.saveAndPrint');
    Route::post('/daily_restrictions/stor', [RestrictionController::class, 'stor'])->name('daily_restrictions.stor');
    Route::put('/daily_restrictions/{id}', [RestrictionController::class, 'update'])->name('daily_restrictions.update');
    Route::delete('/daily_restrictions/{id1}', [RestrictionController::class, 'destroy'])->name('daily_restrictions.destroy');
    Route::get('/general_entries/show', [generalEntrieController::class, 'show'])->name('general_entries.show');
    Route::get('/double_entries/create', [DoubleEntryController::class, 'create'])->name('double_entries.create');
    Route::post('/double_entries/storeOrUpdate', [DoubleEntryController::class, 'storeOrUpdate'])->name('double_entry.storeOrUpdate');
    Route::get('/double_entries/{id}', [DoubleEntryController::class, 'show'])->name('double_entry.show');
    Route::post('/double_entries/store', [DoubleEntryController::class, 'store'])->name('double_entry.store');

    Route::get('/general/{id}/ledger/{accounting_id}', [TransferController::class, 'general_ledger'])->name('general.ledger');
    Route::get('/general/ledger/{accounting_id}', [TransferController::class, 'general'])->name('general');
    Route::get('/transfer_restrictions/create', [TransferController::class, 'create'])->name('transfer_restrictions.create');
    Route::get('/transfer_restrictions/index', [TransferController::class, 'index'])->name('transfer_restrictions.index');
    Route::post('/transfer_restrictions/optional', [TransferController::class, 'optional'])->name('transfer_restrictions.optional');
    Route::get('/transfer_restrictions/record', [TransferController::class, 'record'])->name('transfer_restrictions.record');
    Route::post('/transfer_restrictions/store', [AccountCoctroller::class, 'store'])->name('transfer_restrictions.store');

    Route::get('/Locks_financial_period/index', [LocksFinancialPeriodsController::class, 'index'])->name('Locks_financial_period.index');
    Route::get('/Locks_financial_period/{id}/getProfitAndLossData', [LocksFinancialPeriodsController::class, 'getProfitAndLossData'])->name('Locks_financial_period.getProfitAndLossData');
    Route::get('/refunds/index', [Sale_RefundController::class, 'index'])->name('refunds.index');
    Route::get('/refunds/create/sale', [Sale_RefundController::class, 'create'])->name('sale_refunds.create');
    Route::get('/refunds/show_purchase_refund', [Purchase_RefundController::class, 'show_purchase_refund'])->name('show_purchase_refund');
    Route::get('/refunds/purchase/show', [Purchase_RefundController::class, 'show'])->name('purchase_refund.show');

    Route::get('/refunds/create/purchase', [Purchase_RefundController::class, 'create'])->name('purchase_refunds.create');
    Route::get('/refunds/show/all', [Sale_RefundController::class, 'show_sale_refund'])->name('all_sale_refund');
    Route::get('/refunds/sale/show', [Sale_RefundController::class, 'show'])->name('sale_refunds.show');
    // ------------------------------------------------
    Route::get('/payments', [PaymentCoctroller::class, 'index'])->name('payments.index');
    // Route::get('/refunds', [RefundController::class, 'index'])->name('refunds.index');
    Route::get('/inventory/index', [InventoryController::class, 'index'])->name('inventory.index');
    Route::post('/inventory/store', [InventoryController::class, 'store'])->name('inventory.store');
    Route::post('/inventory/storeProduct', [InventoryController::class, 'newInventory'])->name('inventory.storeProduct');
    Route::get('/inventory/{currency}/edit', [InventoryController::class, 'edit'])->name('inventory.edit');
    Route::delete('/inventory/{id}/destroy', [InventoryController::class, 'destroy'])->name('inventory.destroy');
    Route::delete('/inventory/Invoice/{id}/destroy', [InventoryController::class, 'destroy_invoice'])->name('Inventory.Invoice.destroy');
    Route::get('/inventory/export', [InventoryController::class, 'export'])->name('inventory.export');
    Route::get('/inventory/create', [InventoryController::class, 'create'])->name('inventory.create');
    Route::get('/create-an-inventory-list/createList', [InventoryController::class, 'createList'])->name('inventory.createList');
    Route::get('/inventory/show_inventory', [InventoryController::class, 'show_inventory'])->name('show_inventory');
    Route::get('/inventory/accountingPeriod', [InventoryController::class, 'show_inventoryAccountingPeriod'])->name('accountingPeriod');
    Route::get('/inventory/{id}/show', [InventoryController::class, 'show_inventory'])->name('inventory.show');
    Route::get('/inventory/{id}/print', [InventoryController::class, 'ShowAllProducts'])->name('inventory.print');

    Route::get('/fixedAssets', [FixedAssetsController::class, 'index'])->name('fixed.index');
    Route::get('/usersControl', [UsersController::class, 'index'])->name('users.index');
    Route::get('/usersShow', [UsersController::class, 'show'])->name('users.details');



    // Route::get('/controle',function(){
    // return view('controle');
    // });
    Route::get('/permission_user', [UserPermissionController::class, 'index'])->name('permission_user');
    Route::post('/update-permission', [UserPermissionController::class, 'updatePermission'])->name('update.permission');
    Route::post('/getPermissions', [UserPermissionController::class, 'getPermissions'])->name('get.permissions');
    Route::post('/get-user-permissions', [UserPermissionController::class, 'getUserPermissions'])->name('get.user.permissions');
    Route::post('/permissions/delete', [UserPermissionController::class, 'deletePermission'])->name('delete.user.permission');
    Route::post('/get-user-permissions-table', [UserPermissionController::class, 'getUserPermissionsTable'])->name('get.user.permissions.table');
    Route::post('/permissions/create', [UserPermissionController::class, 'createPermission'])->name('create.permission');
    Route::post('/permissions/grant-all', [UserPermissionController::class, 'grantAllPermissions'])->name('grant.all.permissions');
    Route::post('/permissions/delete-all', [UserPermissionController::class, 'deleteAllPermissions'])->name('delete.all.permissions');

    Route::get('/chart', [ChartController::class, 'index'])->name('chart.index');
    Route::get('bar-chart-data', [ChartController::class, 'getBarChartDate']);

    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::get('/settings/company_data/create', [Company_DataController::class, 'create'])->name('company_data.settings.create');
    Route::post('/settings/company_data', [Company_DataController::class, 'store'])->name('company_data.store');
    Route::get('/settings/currencies/index', [CurrencieController::class, 'index'])->name('settings.currencies.index');
    Route::get('/settings/currencies/create', [CurrencieController::class, 'create'])->name('settings.currencies.create');
    Route::post('/settings/currencies/store', [CurrencieController::class, 'store'])->name('settings.currencies.store');
    Route::get('/settings/{currency}/edit', [CurrencieController::class, 'edit'])->name('settings.currencies.edit');
    Route::put('/settings/{currency}', [CurrencieController::class, 'update'])->name('settings.currencies.update');
    Route::delete('/settings/{currency}', [CurrencieController::class, 'destroy'])->name('settings.currencies.destroy');
    Route::post('set-default-currency', [CurrencieController::class, 'setDefaultCurrency'])->name('set-default-currency');

    Route::get('/settings/warehouse', [WarehouseController::class, 'index'])->name('warehouse.index');
    Route::post('/settings/warehouse/store', [WarehouseController::class, 'store'])->name('settings.warehouse.store');
    Route::get('/settings/warehouse/{id}/edit', [WarehouseController::class, 'edit'])->name('settings.warehouse.edit');
    Route::put('/settings/warehouse/{id}', [WarehouseController::class, 'update'])->name('settings.warehouse.update');
    Route::delete('/settings/warehouse/{id}', [WarehouseController::class, 'destroy'])->name('settings.warehouse.destroy');

    Route::get('/summary', [reportsConreoller::class, 'summary'])->name('report.summary');

    Route::get('/inventoryReport', [reportsConreoller::class, 'inventoryReport'])->name('report.inventoryReport');
    Route::get('/earningsReports', [reportsConreoller::class, 'earningsReports'])->name('report.earningsReports');
    Route::get('/salesReport', [reportsConreoller::class, 'salesReport'])->name('report.salesReport');
    Route::get('/salesReport/create', [reportsConreoller::class, 'salesReport_create'])->name('salesReport.create');
    Route::get('/SalesReport/print', [reportsConreoller::class, 'print'])->name('SalesReport.print');

    Route::get('/summaryPdf', [reportsConreoller::class, 'summaryPdf'])->name('summaryPdf');
    Route::get('/salesReportPdf', [reportsConreoller::class, 'salesReportPdf'])->name('salesReportPdf');
    Route::get('/inventoryReportPdf', [reportsConreoller::class, 'inventoryReportPdf'])->name('inventoryReportPdf');
    Route::get('/earningsReportsPdf', [reportsConreoller::class, 'earningsReportsPdf'])->name('earningsReportsPdf');


    Route::get('/accounts/large-main-accounts/{largeAccountType}', [TreeAccountController::class, 'getMainAccountsByLargeAccountType'])->name('getMainAccountsByLargeAccountType');
    Route::get('/main-accounts/{id}/sub-accounts', [MainaccountController::class, 'getSubAccounts'])->name('sub-accounts');
    Route::get('/accounts', [AccountCoctroller::class, 'index'])->name('accounts.index');
    Route::get('/accounts/Main_Account/create-sub-account', [SubaccountController::class, 'create'])->name('Main_Account.create-sub-account');
    Route::get('/accounts/subAccount/allShow', [SubaccountController::class, 'allShow'])->name('subAccounts.allShow');
    Route::get('/accounts/subAccount/search', [SubaccountController::class, 'search'])->name('subAccounts.search');
    Route::get('/accounts/subAccount/{id}/edit', [SubaccountController::class, 'edit'])->name('subAccounts.edit');
    Route::post('/accounts/subAccount', [SubaccountController::class, 'update'])->name('subAccounts.update');
    Route::delete('/accounts/subAccount/{id}', [SubaccountController::class, 'destroy'])->name('subAccounts.destroy');
    Route::get('/accounts/account_tree/index_account_tree', [AccountCoctroller::class, 'index_account_tree'])->name('index_account_tree');
    Route::get('/accounts/show/main-accounts', [AccountCoctroller::class, 'showMainAccount'])->name('showMainAccount');
    Route::get('/search-sub-accounts', [TreeAccountController::class, 'searchSubAccounts'])->name('search.sub.accounts');
    Route::post('/accounts/create-sub-account/store', [MainaccountController::class, 'store'])->name('Main_Account.store');

    Route::get('/accounts/Main_Account/create', [MainaccountController::class, 'create'])->name('Main_Account.create');
    Route::get('/accounts/review-budget/{year}', [Review_BudgetController::class, 'review_budget'])->name('accounts.review_budget');
    Route::post('/accounts/Main_Account/storc', [MainaccountController::class, 'storc'])->name('Main_Account.storc');
    Route::get('/accounts/Main_Account/{id}/edit', [MainaccountController::class, 'edit'])->name('accounts.Main_Account.edit');
    Route::delete('/accounts/Main_Account/{id}', [MainaccountController::class, 'destroy'])->name('accounts.Main_Account.destroy');
    Route::get('/accounts/main-accounts/{type}', [MainaccountController::class, 'getMainAccountsByType']);
    Route::put('/accounts/Main_Account/{id}', [MainaccountController::class, 'update'])->name('accounts.Main_Account.update');

    // Route::get('/search', [MainaccountController::class, 'search']);
    Route::get('/get-options', [AccountCoctroller::class, 'show_all_accounts']);
    Route::get('/', [HomeCoctroller::class, 'index'])->name('home.index');

    // Account Tree API Routes
    Route::get('/api/accounts/by-type/{type}', [TreeAccountController::class, 'getAccountsByType'])->name('api.accounts.by-type');
    Route::get('/api/accounts/{mainAccount}/sub-accounts', [TreeAccountController::class, 'getSubAccounts'])->name('api.accounts.sub-accounts');
});

Route::post('/executeC', [ArtController::class, 'index'])->name('executeC');
Route::get('/suppliers', [SupplierCoctroller::class, 'index'])->name('suppliers.index');
Route::get('/dashboard', [DashboardController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::post('/run-migration', function () {
    try {
        // Artisan::call('migrate');  // تنفيذ أمر migrate
        Artisan::call('migrate', [
            '--force' => true  // تأكيد الترحيل في بيئة الإنتاج
        ]);

        return redirect()->back()->with('success', 'تم تنفيذ الترحيل بنجاح');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'حدث خطأ أثناء تنفيذ الترحيل: ');
    }
})->name('run_migration');

Route::middleware('auth')->group(function () {



    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
