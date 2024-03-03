<?php

use App\Http\Controllers\CashRegisterController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SaleReturnController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
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

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::resource('users', UserController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('/purchases', PurchaseController::class);
    Route::resource('/sales', SaleController::class);
    Route::resource('/products', ProductController::class);
    Route::resource('/expense_categories', ExpenseCategoryController::class);
    Route::resource('/expenses', ExpenseController::class);
    Route::resource('/sale_returns', SaleReturnController::class);
    Route::resource('/products', ProductController::class);
    Route::resource('/payment_methods', PaymentMethodController::class);
    Route::post('customer-payment',[PaymentController::class,'customerPayment'])->name('customer.make.payment');
    Route::post('supplier-payment',[PaymentController::class,'supplierPayment'])->name('supplier.make.payment');
    Route::resource('/payments', PaymentController::class);
    Route::get('dataCustomers',[CustomerController::class,'dataCustomers']);
    Route::get('dataSuppliers',[SupplierController::class,'dataSuppliers']);
    Route::resource('/cash_registers', CashRegisterController::class);
    Route::get('daily-report',[ReportController::class,'dailyReport'])->name('report.daily');
    Route::get('payment-report',[ReportController::class,'paymentReport'])->name('report.payment');
});
Route::resource('/balance_transfers', App\Http\Controllers\BalanceTransferController::class);
Route::resource('/accounts', App\Http\Controllers\AccountController::class);
Route::resource('/transactions', App\Http\Controllers\TransactionController::class);

Route::get('customers-transaction',[\App\Http\Controllers\TransactionController::class,'customerTransactions'])->name('transactions.customer');
Route::get('suppliers-transaction',[\App\Http\Controllers\TransactionController::class,'supplierTransactions'])->name('transactions.supplier');
Route::resource('/loans', App\Http\Controllers\LoanController::class);
Route::get('loan-transactions',[\App\Http\Controllers\LoanController::class,'loanTransactions'])->name('loans.transactions');
Route::post('loan-repayment',[\App\Http\Controllers\LoanController::class,'loanRepayment'])->name('loans.repayment');
Route::get('data-products',[ProductController::class,'dataProducts'])->name('data.products');
Route::resource('/asset', App\Http\Controllers\AssetController::class);
Route::get('report/balance-sheet',[ReportController::class,'balanceSheet'])->name('report.balance.sheet');
