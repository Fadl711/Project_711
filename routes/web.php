<?php

use App\Http\Controllers\CustomerCoctroller;
use App\Http\Controllers\HomeCoctroller;
use App\Http\Controllers\ProductCoctroller;
use App\Http\Controllers\AccountCoctroller;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\PaymentCoctroller;
use App\Http\Controllers\SaleCoctroller;


use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseCoctroller;
use App\Http\Controllers\RefundController;
use App\Http\Controllers\SupplierCoctroller;
use App\Http\Controllers\PDFReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home.indxe');
});
Route::get('/sales', [SaleCoctroller::class, 'index'])->name('sales.index');
Route::get('/products', [ProductCoctroller::class, 'index'])->name('products.index');
Route::get('/Purchase', [PurchaseCoctroller::class, 'Purchase'])->name('Purchases.index');
Route::get('/accounts', [AccountCoctroller::class, 'index'])->name('accounts.index');
Route::get('/payments', [PaymentCoctroller::class, 'index'])->name('payments.index');
Route::get('/refunds', [RefundController::class, 'index'])->name('refunds.index');
Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');

Route::get('/reports/pdf', [PDFReportController::class, 'createPDF'])->name('donwload');




Route::get('/customers', [CustomerCoctroller::class, 'index'])->name('customers.index');
Route::get('/suppliers', [SupplierCoctroller::class, 'index'])->name('suppliers.index');

Route::get('/home', [HomeCoctroller::class, 'indxe'])->name('home.indxe');



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
