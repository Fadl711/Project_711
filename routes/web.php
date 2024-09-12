<?php

use App\Http\Controllers\CustomerCoctroller;
use App\Http\Controllers\HomeCoctroller;
use App\Http\Controllers\ProductCoctroller;
use App\Http\Controllers\AccountCoctroller;
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
use App\Http\Controllers\refundsController\purchasesController\Purchase_RefundController;
use App\Http\Controllers\refundsController\saleController\RefundController as SaleControllerRefundController;
use App\Http\Controllers\refundsController\salesController\Sale_RefundController;
use App\Http\Controllers\reportsConreoller;
use App\Http\Controllers\settingController\SettingController;
use App\Http\Controllers\UsersController\UsersController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home.index');
});
Route::get('/sales', [SaleCoctroller::class, 'index'])->name('sales.index');
Route::get('/products', [ProductCoctroller::class, 'index'])->name('products.index');
Route::get('/Purchase', [PurchaseCoctroller::class, 'Purchase'])->name('Purchases.index');
Route::get('/accounts', [AccountCoctroller::class, 'index'])->name('accounts.index');
Route::get('/balancing', [AccountCoctroller::class, 'balancing'])->name('accounts.balancing');
Route::get('/invoice_sales', [AllBillsController::class, 'index'])->name('invoice_sales.index');
Route::get('/all_bills_sale', [AllBillsController::class, 'all_bills_sale'])->name('invoice_sales.all_bills_sale');
Route::get('/bills_sale_show', [AllBillsController::class, 'bills_sale_show'])->name('invoice_sales.bills_sale_show');
Route::get('/invoice_purchases', [InvoicePurchaseController::class, 'index'])->name('invoice_purchase.index');

// Print PDF
Route::get('/PDF1', [PDFReportController::class, 'sales'])->name('print.sales');
Route::get('/PDF2', [PDFReportController::class, 'purchase'])->name('print.purchase');
// Print PDF

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

Route::get('/refunds/create/purchase', [Purchase_RefundController::class, 'create'])->name('purchase_refunds.create');


















Route::get('/payments', [PaymentCoctroller::class, 'index'])->name('payments.index');
// Route::get('/refunds', [RefundController::class, 'index'])->name('refunds.index');
Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
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

Route::get('/report',[reportsConreoller::class,'index'])->name('report.index');




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
