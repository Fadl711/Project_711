<?php

use App\Http\Controllers\CustomerCoctroller;
use App\Http\Controllers\HomeCoctroller;
use App\Http\Controllers\AccountCoctroller;
use App\Http\Controllers\Accounts\main_accounts\MainaccountController;
use App\Http\Controllers\Accounts\SubaccountsController;
use App\Http\Controllers\bondController\BondController;
use App\Http\Controllers\bondController\exchangeController\ExchangeController;
use App\Http\Controllers\bondController\receipController\All_Receipt_BondController;
use App\Http\Controllers\bondController\receipController\ReceipController;
use App\Http\Controllers\chartController\ChartController;
use App\Http\Controllers\DailyRestrictionController\RestrictionController;
use App\Http\Controllers\FixedAssetsController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\invoicepurchasessController\InvoicePurchaseController;
use App\Http\Controllers\invoicesController\AllBillsController;
use App\Http\Controllers\invoicesController\InvoiceController;
use App\Http\Controllers\PaymentCoctroller;
use App\Http\Controllers\SaleCoctroller;


use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseCoctroller;
use App\Http\Controllers\RefundController;
use App\Http\Controllers\SupplierCoctroller;
use App\Http\Controllers\PDFReportController;
use App\Http\Controllers\productController\CategoryController;
use App\Http\Controllers\productController\ProductCoctroller;
use App\Http\Controllers\refundsController\purchasesController\Purchase_RefundController;
use App\Http\Controllers\refundsController\saleController\RefundController as SaleControllerRefundController;
use App\Http\Controllers\refundsController\salesController\Sale_RefundController;
use App\Http\Controllers\reportsConreoller;
use App\Http\Controllers\settingController\company_dataController\Company_DataController;
use App\Http\Controllers\settingController\currenciesController\CurrencieController;
use App\Http\Controllers\settingController\SettingController;
use App\Http\Controllers\UsersController\UsersController;
use App\Models\Sub_Account;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home.index');
});
Route::get('/sales', [SaleCoctroller::class, 'index'])->name('sales.index');

Route::get('/products', [ProductCoctroller::class, 'index'])->name('products.index');
Route::get('/products/create', [ProductCoctroller::class, 'create'])->name('products.create');
Route::post('/products/store', [ProductCoctroller::class, 'store'])->name('products.store');
Route::get('/products/{product}/edit',[ProductCoctroller::class,'edit'])->name('products.edit');
Route::put('/products/{product}',[ProductCoctroller::class,'update'])->name('products.update');
Route::delete('/products/{product}',[ProductCoctroller::class,'destroy'])->name('products.destroy');
Route::post('/search', [ProductCoctroller::class,'search'])->name('search.perform');

Route::get('/products/Category', [CategoryController::class, 'create'])->name('Category.create');
Route::post('/Category/store',[CategoryController::class,'store'])->name('Category.store');
Route::get('/Category/{Category}/edit', [CategoryController::class, 'edit'])->name('Category.edit');
Route::put('/Category/{Category}', [CategoryController::class, 'update'])->name('Category.update');
Route::delete('/Category/{Category}',[CategoryController::class,'destroy'])->name('Category.destroy');



Route::get('/Purchase', [PurchaseCoctroller::class, 'Purchase'])->name('Purchases.index');

Route::get('/balancing', [AccountCoctroller::class, 'balancing'])->name('accounts.balancing');
Route::get('/invoice_sales', [AllBillsController::class, 'index'])->name('invoice_sales.index');
Route::get('/all_bills_sale', [AllBillsController::class, 'all_bills_sale'])->name('invoice_sales.all_bills_sale');
Route::get('/bills_sale_show', [AllBillsController::class, 'bills_sale_show'])->name('invoice_sales.bills_sale_show');
Route::get('/invoice_purchases', [InvoicePurchaseController::class, 'index'])->name('invoice_purchase.index');
Route::get('/PDF', [PDFReportController::class, 'createPDF']);


Route::get('/bonds', [BondController::class, 'bonds'])->name('bonds.index');
Route::get('/Receip/create', [ReceipController::class, 'create'])->name('create.index');
Route::get('/Receip/show_all_receipt', [All_Receipt_BondController::class, 'show_all_receipt'])->name('show_all_receipt');
Route::get('/Receip/show', [ReceipController::class, 'show'])->name('receip.show');
Route::get('/Receip/edit', [ReceipController::class, 'edit'])->name('receip.edit');

Route::get('/exchange/index', [ExchangeController::class, 'index'])->name('exchange.index');
Route::get('/exchange/all_exchange_bonds', [ExchangeController::class, 'all_exchange_bonds'])->name('all_exchange_bonds');
Route::get('/exchange/show', [ExchangeController::class, 'show'])->name('exchange.show');
Route::get('/exchange/edit', [ExchangeController::class, 'edit'])->name('exchange.edit');

Route::get('/restrictions/create', [RestrictionController::class, 'create'])->name('restrictions.create');
Route::get('/restrictions/index', [RestrictionController::class, 'index'])->name('restrictions.index');
Route::get('/restrictions/all_restrictions_show', [RestrictionController::class, 'all_restrictions_show'])->name('all_restrictions_show');
Route::get('/restrictions/edit', [RestrictionController::class, 'edit'])->name('restrictions.edit');
Route::get('/restrictions/show', [RestrictionController::class, 'show'])->name('restrictions.show');
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
Route::get('/inventory/create', [InventoryController::class, 'create'])->name('inventory.create');
Route::get('/inventory/show_inventory', [InventoryController::class, 'show_inventory'])->name('show_inventory');
Route::get('/inventory/show', [InventoryController::class, 'show'])->name('inventory.show');




Route::get('/fixedAssets', [FixedAssetsController::class, 'index'])->name('fixed.index');
Route::get('/usersControl', [UsersController::class, 'index'])->name('users.index');
Route::get('/usersShow', [UsersController::class, 'show'])->name('users.details');

Route::get('/reports/pdf', [PDFReportController::class, 'createPDF'])->name('donwload');

Route::get('/controle',function(){
return view('controle');
});
Route::get('/chart',[ChartController::class,'index'])->name('chart.index');
Route::get('bar-chart-data',[ChartController::class, 'getBarChartDate']);

Route::get('/settings',[SettingController::class,'index'])->name('settings.index');
Route::get('/settings/company_data/create',[Company_DataController::class,'create'])->name('company_data.settings.create');
Route::post('/settings/company_data/{data}',[Company_DataController::class,'store'])->name('company_data.store');
Route::get('/settings/currencies/index',[CurrencieController::class,'index'])->name('settings.currencies.index');


Route::get('/summary',[reportsConreoller::class,'summary'])->name('report.summary');
Route::get('/inventoryReport',[reportsConreoller::class,'inventoryReport'])->name('report.inventoryReport');
Route::get('/earningsReports',[reportsConreoller::class,'earningsReports'])->name('report.earningsReports');
Route::get('/salesReport',[reportsConreoller::class,'salesReport'])->name('report.salesReport');
Route::get('/summaryPdf',[reportsConreoller::class,'summaryPdf'])->name('summaryPdf');
Route::get('/salesReportPdf',[reportsConreoller::class,'salesReportPdf'])->name('salesReportPdf');
Route::get('/inventoryReportPdf',[reportsConreoller::class,'inventoryReportPdf'])->name('inventoryReportPdf');
Route::get('/earningsReportsPdf',[reportsConreoller::class,'earningsReportsPdf'])->name('earningsReportsPdf');


Route::post('/add_account/store', [SubaccountsController::class, 'store'])->name('add_account.store');
Route::get('/accounts', [AccountCoctroller::class, 'index'])->name('accounts.index');
Route::get('/accounts/Sub_Account/create', [SubaccountsController::class, 'create'])->name('Sub_Account.create');
Route::post('/accounts/Sub_Account/store', [SubaccountsController::class, 'store'])->name('Sub_Account.store');

Route::get('/accounts/Main_Account/create', [MainaccountController::class, 'create'])->name('Main_Account.create');
Route::post('/accounts/Main_Account/store', [MainaccountController::class, 'store'])->name('Main_Account.store');
// Route::post('/accounts/Main_Account/storc', [MainaccountController::class, 'storc'])->name('Main_Account.storc');

// Route::get('/search', [MainaccountController::class, 'search']);
Route::get('/get-options', [AccountCoctroller::class, 'show_all_accounts']);






Route::get('/customers', [CustomerCoctroller::class, 'index'])->name('customers.index');
Route::get('/suppliers', [SupplierCoctroller::class, 'index'])->name('suppliers.index');

Route::get('/home', [HomeCoctroller::class, 'indxe'])->name('home.index');



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
