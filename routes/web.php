<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Auth::routes(['register'=> false]);

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('users', App\Http\Controllers\UserController::class);
Route::resource('customers', App\Http\Controllers\CustomerController::class);
Route::resource('suppliers', App\Http\Controllers\SupplierController::class);
Route::resource('/purchases', App\Http\Controllers\PurchaseController::class);
Route::resource('/sales', App\Http\Controllers\SaleController::class);
Route::resource('/products', App\Http\Controllers\ProductController::class);
Route::resource('/expense_categories', App\Http\Controllers\ExpenseCategoryController::class);
Route::resource('/expenses', App\Http\Controllers\ExpenseController::class);

Route::resource('/sale_returns', App\Http\Controllers\SaleReturnController::class);
Route::resource('/products', App\Http\Controllers\ProductController::class);
Route::resource('/payment_methods', App\Http\Controllers\PaymentMethodController::class);

Route::post('customer-payment',[\App\Http\Controllers\PaymentController::class,'customerPayment'])->name('customer.make.payment');
Route::post('supplier-payment',[\App\Http\Controllers\PaymentController::class,'supplierPayment'])->name('supplier.make.payment');
Route::resource('/payments', App\Http\Controllers\PaymentController::class);
