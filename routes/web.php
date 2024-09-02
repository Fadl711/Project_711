<?php

use App\Http\Controllers\CustomerCoctroller;
use App\Http\Controllers\HomeCoctroller;
use App\Http\Controllers\ProductCoctroller;
use App\Http\Controllers\AccountCoctroller;
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



Route::get('/payments', [PaymentCoctroller::class, 'index'])->name('payments.index');
Route::get('/refunds', [RefundController::class, 'index'])->name('refunds.index');
Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
Route::get('/fixedAssets', [FixedAssetsController::class, 'index'])->name('fixed.index');
Route::get('/usersControl', [UsersController::class, 'index'])->name('users.index');
Route::get('/usersShow', [UsersController::class, 'show'])->name('users.details');

Route::get('/reports/pdf', [PDFReportController::class, 'createPDF'])->name('donwload');




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
