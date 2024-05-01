<?php

use App\Http\Controllers\CashRegisterController;
use App\Http\Controllers\ChartController;
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
use Illuminate\Http\Request;
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


Auth::routes(['register' => false]);
Route::get('/welcome', function () {
    return view('welcome');
});
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::middleware(['auth'])->group(function () {
    //Route::get('/home', [HomeController::class, 'index'])->name('home');
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
    Route::post('customer-payment', [PaymentController::class, 'customerPayment'])->name('customer.make.payment');
    Route::post('supplier-payment', [PaymentController::class, 'supplierPayment'])->name('supplier.make.payment');
    Route::resource('/payments', PaymentController::class);
    Route::get('dataCustomers', [CustomerController::class, 'dataCustomers']);
    Route::get('dataSuppliers', [SupplierController::class, 'dataSuppliers']);
    Route::resource('/cash_registers', CashRegisterController::class);
    Route::get('daily-report', [ReportController::class, 'dailyReport'])->name('report.daily');
    Route::get('payment-report', [ReportController::class, 'paymentReport'])->name('report.payment');
    Route::get('stock-report', [ReportController::class, 'stockReport'])->name('report.stock');
    Route::get('sales-report', [ReportController::class, 'salesReport'])->name('report.sales');
    Route::get('purchases-report', [ReportController::class, 'purchasesReport'])->name('report.purchases');
    Route::get('supplier-balance-report', [ReportController::class, 'supplierReport'])->name('report.supplier.balance');
    Route::get('customer-balance-report', [ReportController::class, 'customerReport'])->name('report.customer.balance');
    Route::get('purchase-sales-report', [ReportController::class, 'purchaseSaleReport'])->name('report.purchase.sales');
    Route::get('profit-loss-report', [ReportController::class, 'profitLoss'])->name('report.profit.loss');

    Route::resource('/balance_transfers', App\Http\Controllers\BalanceTransferController::class);
    Route::resource('/accounts', App\Http\Controllers\AccountController::class);
    Route::resource('/transactions', App\Http\Controllers\TransactionController::class);

    Route::get('customers-transaction', [\App\Http\Controllers\TransactionController::class, 'customerTransactions'])->name('transactions.customer');
    Route::get('suppliers-transaction', [\App\Http\Controllers\TransactionController::class, 'supplierTransactions'])->name('transactions.supplier');
    Route::resource('/loans', App\Http\Controllers\LoanController::class);
    Route::get('loan-transactions', [\App\Http\Controllers\LoanController::class, 'loanTransactions'])->name('loans.transactions');
    Route::post('loan-repayment', [\App\Http\Controllers\LoanController::class, 'loanRepayment'])->name('loans.repayment');
    Route::get('data-products', [ProductController::class, 'dataProducts'])->name('data.products');
    Route::resource('/asset', App\Http\Controllers\AssetController::class);
    Route::get('report/balance-sheet', [ReportController::class, 'balanceSheet'])->name('report.balance.sheet');

    Route::post('supplier-payment', [\App\Http\Controllers\TransactionController::class, 'supplierPayment'])->name('supplier.payment.store');
    Route::post('customer-payment', [\App\Http\Controllers\TransactionController::class, 'customerPayment'])->name('customer.payment.store');
    Route::resource('/sale_returns', App\Http\Controllers\SaleReturnController::class);
    Route::resource('/purchase_returns', App\Http\Controllers\PurchaseReturnController::class);
    Route::resource('/sale_returns', App\Http\Controllers\SaleReturnController::class);
    Route::resource('/income_categories', App\Http\Controllers\IncomeCategoryController::class);
    Route::resource('/incomes', App\Http\Controllers\IncomeController::class);
    Route::get('dataSales', [SaleController::class, 'dataSales'])->name('data.sales');
    Route::get('dataPurchases', [PurchaseController::class, 'dataPurchases'])->name('data.purchases');
    Route::get('dataExpenses', [ExpenseController::class, 'dataExpenses'])->name('data.expenses');
    Route::get('dataIncomes', [\App\Http\Controllers\IncomeController::class, 'dataIncomes'])->name('data.incomes');
    Route::resource('/capitals', App\Http\Controllers\CapitalController::class);
    Route::get('capital-transactions', [\App\Http\Controllers\CapitalController::class, 'capitalTransactions'])->name('capital.transactions');
    Route::post('capital-withdraw', [\App\Http\Controllers\CapitalController::class, 'capitalWithdraw'])->name('capital.repayment');
    Route::resource('/capital_withdraws', App\Http\Controllers\CapitalWithdrawController::class);
    Route::resource('/loan_repayments', App\Http\Controllers\LoanRepaymentController::class);
    Route::resource('/bank_loans', App\Http\Controllers\BankLoanController::class);
    Route::resource('/bank_loan_repayments', App\Http\Controllers\BankLoanRepaymentController::class);
    Route::resource('/investments', App\Http\Controllers\InvestmentController::class);
    Route::resource('/investment_repayments', App\Http\Controllers\InvestmentRepaymentController::class);
    Route::resource('/employees', App\Http\Controllers\EmployeeController::class);
    Route::resource('/salaries', App\Http\Controllers\SalaryController::class);
    Route::resource('/payments', App\Http\Controllers\PaymentController::class);
    Route::resource('/payments', App\Http\Controllers\PaymentController::class);
    Route::resource('/tohoris', App\Http\Controllers\TohoriController::class);
    Route::get('chart-income-expense', [ChartController::class, 'incomeExpense'])->name('chart.income.expense');
    Route::get('chart-sales-purchases', [ChartController::class, 'salesPurchases'])->name('chart.sales.purchases');
    Route::get('chart-daily-sales-and-purchase', [ChartController::class, 'getDailySalesAndPurchaseData'])->name('chart.daily.sales.purchases');
    Route::get('getDailySalePurchaseChartData', [App\Http\Controllers\Chart\SalePurchaseChartController::class, 'getDailySalePurchaseChartData'])->name('daily.sale.purchase.chart.data');
    Route::resource('/asset_sells', App\Http\Controllers\AssetSellController::class);

    //Export
    Route::get('purchase/export', [App\Http\Controllers\Export\PurchaseExportController::class,'exportToCSV'])->name('purchase.export');
    Route::get('sale/export', [App\Http\Controllers\Export\SaleExportController::class,'exportToCSV'])->name('sale.export');
    Route::get('sale-product/export', [App\Http\Controllers\Export\SaleProductExportController::class,'exportToCSV'])->name('sale.product.export');
    Route::get('purchase-product/export', [App\Http\Controllers\Export\PurchaseProductExportController::class,'exportToCSV'])->name('purchase.product.export');

    Route::get('export',[\App\Http\Controllers\Export\ExportController::class,'index']);
    Route::resource('/sale_details', App\Http\Controllers\SaleDetailController::class);
    Route::get('/download-csv/{filename}', function ($filename) {
        $filePath = storage_path('app/public/csv/' . $filename);
        return response()->download($filePath);
    })->name('download_csv');

    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class,'index'])->name('notifications.index');

    Route::get('dataExpenseByCategory',[ExpenseCategoryController::class,'dataExpenseByCategory'])->name('dataExpenseByCategory');
    Route::get('dataIncomeByCategory',[\App\Http\Controllers\IncomeCategoryController::class,'dataIncomeByCategory'])->name('dataIncomeByCategory');

    Route::resource('/purchase_details', App\Http\Controllers\PurchaseDetailController::class);

});

Route::resource('/initial-balances', App\Http\Controllers\InitialBalanceController::class);
Route::get('supplier-list',[SupplierController::class,'supplierList']);
Route::post('store-supplier',[SupplierController::class,'storeSupplier']);
