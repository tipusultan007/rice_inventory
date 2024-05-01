<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Asset;
use App\Models\BankLoan;
use App\Models\BankLoanRepayment;
use App\Models\Capital;
use App\Models\CapitalWithdraw;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Investment;
use App\Models\InvestmentRepayment;
use App\Models\Loan;
use App\Models\LoanRepayment;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnDetail;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\SaleReturn;
use App\Models\SaleReturnDetail;
use App\Models\Supplier;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function dailyReport(Request $request)
    {
        $date = $request->input('date', date('Y-m-d'));
        /*$sales = Sale::with('saleDetails', 'customer')
            ->where('date', $date)
            ->orderByDesc('id')
            ->get();
        $purchases = Purchase::with('purchaseDetails', 'supplier')
            ->where('date', $date)
            ->orderByDesc('id')
            ->get();

        // Retrieve all purchase details for the given date
        $purchaseDetails = PurchaseDetail::whereHas('purchase', function ($query) use ($date) {
            $query->where('date', '<=', $date);
        })->get();

        // Retrieve all sale details for the given date
        $saleDetails = SaleDetail::whereHas('sale', function ($query) use ($date) {
            $query->where('date', '<=', $date);
        })->get();

        // Group purchase details by product ID and sum the quantities
        $totalPurchaseQuantities = $purchaseDetails->groupBy('product_id')->map(function ($items) {
            return $items->sum('quantity');
        });

        // Group sale details by product ID and sum the quantities
        $totalSaleQuantities = $saleDetails->groupBy('product_id')->map(function ($items) {
            return $items->sum('quantity');
        });

        // Calculate the net quantity for each product
        $productQuantities = $totalPurchaseQuantities->merge($totalSaleQuantities)->map(function ($total, $productId) use ($totalSaleQuantities) {
            $totalSaleQuantity = $totalSaleQuantities->get($productId, 0);
            return $total - $totalSaleQuantity;
        });

        // Retrieve product information based on product IDs
        $products25 = Product::where('type', '25')->whereIn('id', $productQuantities->keys())->get();
        $products50 = Product::where('type', '50')->whereIn('id', $productQuantities->keys())->get();

        // Combine product information with quantities
        $productData25 = $products25->map(function ($product) use ($productQuantities) {
            $quantity = $productQuantities->get($product->id, 0);
            return [
                'product_name' => $product->name,
                'quantity' => $quantity,
            ];
        });
        $productData50 = $products50->map(function ($product) use ($productQuantities) {
            $quantity = $productQuantities->get($product->id, 0);
            return [
                'product_name' => $product->name,
                'quantity' => $quantity,
            ];
        });*/


        $debitTransactions = Transaction::where('type', 'debit')
            ->whereNotNull('account_id')
            ->whereDate('date', $date)
            ->with('account')
            ->select('account_id', DB::raw('SUM(amount) as total_amount'))
            ->groupBy('account_id')
            ->get();

        // Filter credit transactions for the specific date and group by account
        $creditTransactions = Transaction::where('type', 'credit')
            ->whereNotNull('account_id')
            ->whereDate('date', $date)
            ->with('account')
            ->select('account_id', DB::raw('SUM(amount) as total_amount'))
            ->groupBy('account_id')
            ->get();

        $sales = Sale::with('saleDetails')
            ->where('date',$date)
            ->get();
        $purchases = Purchase::with('purchaseDetails')
            ->where('date',$date)
            ->get();

        $saleReturns = SaleReturn::with('saleReturnDetail')
            ->where('date',$date)
            ->get();
        $purchaseReturns = PurchaseReturn::with('purchaseReturnDetail')
            ->where('date',$date)
            ->get();


        $expenses = Expense::where('date',$date)->join('expense_categories', 'expenses.expense_category_id', '=', 'expense_categories.id')
            ->groupBy('expenses.expense_category_id','expense_categories.name')
            ->select('expense_categories.name as category', \DB::raw('SUM(expenses.amount) as total'))
            ->get();

        $incomes = Income::where('date',$date)->join('income_categories', 'incomes.income_category_id', '=', 'income_categories.id')
            ->groupBy('incomes.income_category_id','income_categories.name')
            ->select('income_categories.name as category', \DB::raw('SUM(incomes.amount) as total'))
            ->get();

        $purchaseDetails = PurchaseDetail::whereHas('purchase', function ($query) use ($date) {
            $query->where('date', '<=', $date);
        })->get();



        // Retrieve all sale details for the given date
        $saleDetails = SaleDetail::whereHas('sale', function ($query) use ($date) {
            $query->where('date', '<=', $date);
        })->get();




        // Group purchase details by product ID and sum the quantities
        $totalPurchaseQuantities = $purchaseDetails->groupBy('product_id')->map(function ($items) {
            return $items->sum('quantity');
        });


        // Group sale details by product ID and sum the quantities
        $totalSaleQuantities = $saleDetails->groupBy('product_id')->map(function ($items) {
            return $items->sum('quantity');
        });


        $productData25 = Product::where('type','25')->get();
        $productData50 = Product::where('type','50')->get();

        //return view('reports.daily', compact('sales', 'purchases', 'supplierPayments', 'customerPayments', 'productData25', 'productData50'));
        return view('reports.daily',compact(
            'date',
            'debitTransactions',
            'creditTransactions',
            'sales',
            'purchases',
            'saleReturns',
            'purchaseReturns',
            'expenses',
            'incomes',
            'productData25',
            'productData50',

        ));
    }

    public function balanceSheet(Request $request)
    {

        $date = $request->input('date', date('Y-m-d'));
        $accounts = Account::with(['transactions' => function ($query) use ($date) {
            $query->where('date', '<=', $date);
        }])->get();

        $customer_due = DB::table('transactions')
            ->select(DB::raw('SUM(
            CASE
                WHEN transaction_type = "customer_opening_balance" AND type = "debit" THEN amount
                WHEN transaction_type = "sale" AND type = "credit" THEN amount
                WHEN transaction_type = "customer_payment" AND type = "debit" THEN -amount
                WHEN transaction_type = "discount" AND type = "debit" THEN -amount
                WHEN transaction_type = "payment_to_customer" AND type = "credit" THEN -amount
                ELSE 0
            END
        ) AS total_due'))->value('total_due');

        $assets = Asset::whereDate('date', '<=', $date)->sum('value');

        $supplier_due =DB::table('transactions')
            ->select(DB::raw('SUM(
            CASE
                WHEN transaction_type = "supplier_opening_balance" AND type = "credit" THEN amount
                WHEN transaction_type = "purchase" AND type = "debit" THEN amount
                WHEN transaction_type = "supplier_payment" AND type = "credit" THEN -amount
                WHEN transaction_type = "discount" AND type = "credit" THEN -amount
                WHEN transaction_type = "payment_from_supplier" AND type = "debit" THEN -amount
                ELSE 0
            END
        ) AS total_due'))->value('total_due');

        $loans = Loan::where('date', '<=', $date)->sum('loan_amount');
        $bankloans = BankLoan::where('date', '<=', $date)->sum('total_loan');
        $investments = Investment::where('date', '<=', $date)->sum('loan_amount');
        $loanPaid = LoanRepayment::where('date', '<=', $date)->sum('amount');
        $investmentPaid = InvestmentRepayment::where('date', '<=', $date)->sum('amount');
        $bankloanPaid = BankLoanRepayment::where('date', '<=', $date)->select(DB::raw('SUM(amount) as paid, SUM(grace) as grace'))->first();
        $capitals = Capital::where('date', '<=', $date)->sum('amount');
        $capitalWithdraw = CapitalWithdraw::where('date', '<=', $date)->sum('amount');

        $loanBalance = $this->loanBalance($date);
        $investmentBalance = $this->invesmentBalance($date);
        //$bankloanBalance = $bankloanPaid->paid + $bankloanPaid->grace;
        $bankloanBalance = $this->bankLoanBalance($date);
        //$capitalBalance = $capitals - $capitalWithdraw;
        $capitalBalance = $this->capitalBalance($date);
        $netProfit = $this->getNetProfit($date);
        $assetBalance = $this->assetBalance($date);

        $product = new Product();

        $result = $product->getTotalStockAndValue($date);

        $totalProducts = $result['total_products'];
        $totalValue = $result['total_value'];
        $totalStock = $result['total_stock'];

        return view('reports.balance_sheet',
            compact('accounts',
                'totalProducts',
                'totalValue',
                'totalStock',
                'customer_due',
                'assets',
                'assetBalance',
                'supplier_due',
                'loans',
                'capitals',
                'netProfit',
                'loanBalance',
                'investmentBalance',
                'bankloanBalance',
                'capitalBalance',
            ));
    }

    public function assetBalance($date)
    {
        $assets = Asset::with('assetSells')->where('date', '<=', $date)->get();
        $balance = [];
        foreach ($assets as $asset) {
            $amount = $asset->initial_balance > 0? $asset->initial_balance: $asset->value;
            $balance[] = $amount - $asset->assetSells()->where('date','<=',$date)->sum('purchase_price');
        }

        return array_sum($balance);
    }
    public function invesmentBalance($date)
    {
        $investments = Investment::with('investmentRepayments')->where('date', '<=', $date)->get();
        $balance = [];
        foreach ($investments as $investment) {
            $amount = $investment->initial_balance > 0? $investment->initial_balance: $investment->loan_amount;
            $balance[] = $amount - $investment->investmentRepayments()->where('date','<=',$date)->sum('amount');
        }

        return array_sum($balance);
    }
    public function loanBalance($date)
    {
        $loans = Loan::where('date', '<=', $date)->get();
        $balance = [];
        foreach ($loans as $loan) {
            $amount = $loan->initial_balance > 0? $loan->initial_balance: $loan->loan_amount;
            $balance[] = $amount - $loan->loanRepayments()->where('date', '<=', $date)->sum('amount');
        }

        return array_sum($balance);
    }
    public function capitalBalance($date)
    {
        $capitals = Capital::with('capitalWithdraws')->where('date', '<=', $date)->get();
        $balance = [];
        foreach ($capitals as $capital) {
            $amount = $capital->initial_balance > 0? $capital->initial_balance: $capital->amount;
            $balance[] = $amount - $capital->capitalWithdraws()->where('date','<=',$date)->sum('amount');
        }

        return array_sum($balance);
    }
    public function bankLoanBalance($date)
    {
        $loans = BankLoan::with('loanRepayments')->where('date', '<=', $date)->get();
        $balance = [];
        foreach ($loans as $loan) {
            $total_loan = $loan->initial_balance>0?$loan->initial_balance:$loan->total_loan;
            $paid = $loan->loanRepayments()->where('date', '<=', $date)->sum('amount');
            $grace = $loan->loanRepayments()->where('date', '<=', $date)->sum('grace');
            $balance[] = $total_loan-$paid - $grace;
        }
        return array_sum($balance);
    }
    public function paymentReport(Request $request)
    {
        $methods = Account::all();
        $customers = Customer::all();
        $suppliers = Supplier::select('name', 'address', 'phone', 'id')->get();

        // Get filter parameters from the request
        $customer_id = $request->input('customer_id');
        $supplier_id = $request->input('supplier_id');
        $payment_method_id = $request->input('account_id');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $payment_type = $request->input('transaction_type');


        // Use the filterPayments method from the previous example
        $filteredPayments = $this->filterPayments($customer_id, $supplier_id, $payment_method_id, $start_date, $end_date, $payment_type);

        // Paginate the results with a specified number of items per page
        $perPage = 10;  // You can adjust this based on your requirements
        $payments = $filteredPayments->paginate($perPage)->withQueryString();;

        return view('reports.payment', compact(
            'payments',
            'customers',
            'suppliers',
            'methods',
            'customer_id',
            'supplier_id',
            'payment_method_id',
            'start_date',
            'end_date',
            'payment_type'
        ));
    }

    private function filterPayments($customer_id = null, $supplier_id = null, $payment_method_id = null, $start_date = null, $end_date = null, $transaction_type = null)
    {
        $query = Transaction::query()->orderByDesc('id');

        if ($customer_id !== null) {
            $query->where('customer_id', $customer_id);
        }

        if ($supplier_id !== null) {
            $query->where('supplier_id', $supplier_id);
        }

        if ($payment_method_id !== null) {
            $query->where('account_id', $payment_method_id);
        }
        if ($start_date !== null) {
            $query->where('date', '>=', Carbon::parse($start_date)->startOfDay());
        }

        if ($end_date !== null) {
            $query->where('date', '<=', Carbon::parse($end_date)->endOfDay());
        }
        if ($transaction_type !== null) {
            $query->where('transaction_type', $transaction_type);
        }

        return $query;
    }


    public function stockReport(Request $request)
    {
        $date = $request->input('date', date('Y-m-d'));

        /*// Retrieve all purchase details for the given date
        $purchaseDetails = PurchaseDetail::whereHas('purchase', function ($query) use ($date) {
            $query->where('date', '<=', $date);
        })->get();

        // Retrieve all sale details for the given date
        $saleDetails = SaleDetail::whereHas('sale', function ($query) use ($date) {
            $query->where('date', '<=', $date);
        })->get();

        // Group purchase details by product ID and sum the quantities
        $totalPurchaseQuantities = $purchaseDetails->groupBy('product_id')->map(function ($items) {
            return $items->sum('quantity');
        });

        // Group sale details by product ID and sum the quantities
        $totalSaleQuantities = $saleDetails->groupBy('product_id')->map(function ($items) {
            return $items->sum('quantity');
        });

        // Retrieve initial stock for each product
        $purchaseReturnDetails = PurchaseReturnDetail::whereHas('purchaseReturn', function ($query) use ($date) {
            $query->where('date', '<=', $date);
        })->get();
        $saleReturnDetails = SaleReturnDetail::whereHas('saleReturn', function ($query) use ($date) {
            $query->where('date', '<=', $date);
        })->get();
        $totalPurchaseReturnQuantities = $purchaseReturnDetails->groupBy('product_id')->map(function ($items) {
            return $items->sum('quantity');
        });
        $totalSaleReturnQuantities = $saleReturnDetails->groupBy('product_id')->map(function ($items) {
            return $items->sum('quantity');
        });
        $initialStocks = Product::whereIn('id', $totalPurchaseQuantities->keys())
            ->pluck('initial_stock', 'id');

// Initialize product quantities array
        $productQuantities = [];

// Merge total purchase quantities
        foreach ($totalPurchaseQuantities as $productId => $totalPurchaseQuantity) {
            $totalSaleQuantity = $totalSaleQuantities->get($productId, 0);
            $totalPurchaseReturnQuantity = $totalPurchaseReturnQuantities->get($productId, 0);
            $totalSaleReturnQuantity = $totalSaleReturnQuantities->get($productId, 0);

            $initialStock = $initialStocks->get($productId, 0);

            // Calculate net quantity for each product
            $productQuantities[$productId] = $initialStock + $totalPurchaseQuantity - $totalSaleQuantity - $totalPurchaseReturnQuantity + $totalSaleReturnQuantity;
        }

// Merge total sale quantities for products not found in purchase details
        foreach ($totalSaleQuantities as $productId => $totalSaleQuantity) {
            if (!isset($productQuantities[$productId])) {
                $initialStock = $initialStocks->get($productId, 0);
                $productQuantities[$productId] = $initialStock - $totalSaleQuantity;
            }
        }

// Retrieve product information based on product IDs
        $products25 = Product::where('type', '25')->whereIn('id', array_keys($productQuantities))->get();
        $products50 = Product::where('type', '50')->whereIn('id', array_keys($productQuantities))->get();

// Combine product information with quantities
        $productData25 = $products25->map(function ($product) use ($productQuantities) {
            $quantity = $productQuantities[$product->id];
            return [
                'product_name' => $product->name,
                'quantity' => $quantity,
                'price_rate' => $product->price_rate,
            ];
        });

        $productData50 = $products50->map(function ($product) use ($productQuantities) {
            $quantity = $productQuantities[$product->id];
            return [
                'product_name' => $product->name,
                'quantity' => $quantity,
                'price_rate' => $product->price_rate,
            ];
        });*/

        $productData25 = Product::where('type','25')->get();
        $productData50 = Product::where('type','50')->get();

        return view('reports.stock', compact('productData25', 'productData50','date'));
    }

    public function purchasesReport(Request $request)
    {
        $date1 = $request->input('date1', date('Y-m-d'));
        $date2 = $request->input('date2', date('Y-m-d'));

        $purchases = Purchase::with('purchaseDetails', 'supplier')
            ->whereBetween('date', [$date1, $date2])
            ->orderBy('date', 'asc')
            ->get();

        return view('reports.purchases', compact('purchases', 'date1', 'date2'));
    }

    public function salesReport(Request $request)
    {
        $date1 = $request->input('date1', date('Y-m-d'));
        $date2 = $request->input('date2', date('Y-m-d'));

        $sales = Sale::with('saleDetails', 'customer')
            ->whereBetween('date', [$date1, $date2])
            ->orderBy('date', 'asc')
            ->get();

        return view('reports.sales', compact('sales', 'date1', 'date2'));
    }

    public function customerReport()
    {
        $customers = Customer::all();
        return view('reports.customer', compact('customers'));
    }

    public function supplierReport()
    {
        $suppliers = Supplier::all();
        return view('reports.supplier', compact('suppliers'));
    }

    public function purchaseSaleReport(Request $request)
    {

        $date1 = $request->input('date1', '2010-01-01');
        $date2 = $request->input('date2', date('Y-m-d'));

        $sales = Sale::whereBetween('date', [$date1, $date2])->sum('total');
        $saleReturns = SaleReturn::whereBetween('date', [$date1, $date2])->sum('total');

        $openingStockBalance = $this->openingStockBalance($date1);
        $closingStockBalance = $this->closingStockBalance($date2);

        $purchases = Purchase::whereBetween('date', [$date1, $date2])->sum('total');
        $purchaseReturns = PurchaseReturn::whereBetween('date', [$date1, $date2])->sum('total');

        $incomes = Income::whereBetween('date', [$date1, $date2])->sum('amount');
        $expenses = Expense::whereBetween('date', [$date1, $date2])->sum('amount');

        return view('reports.purchase_sales',compact(
            'sales',
            'saleReturns',
            'openingStockBalance',
            'closingStockBalance',
            'purchases',
            'purchaseReturns',
            'incomes',
            'expenses',
        ));
    }

    public function getNetProfit($date)
    {

        $sales = Sale::where('date', '<=', $date)->sum('total');
        $saleReturns = SaleReturn::where('date', '<=', $date)->sum('total');

        $openingStockBalance = $this->openingStockBalance($date);
        $closingStockBalance = $this->closingStockBalance($date);

        $purchases = Purchase::where('date', '<=', $date)->sum('total');
        $purchaseReturns = PurchaseReturn::where('date', '<=', $date)->sum('total');

        $incomes = Income::where('date', '<=', $date)->sum('amount');
        $expenses = Expense::where('date', '<=', $date)->sum('amount');

        $netSales = $sales - $saleReturns;
        $netPurchases = $openingStockBalance + $purchases - $purchaseReturns - $closingStockBalance;
        $grossProfit = $netSales - $netPurchases;
        $totalIncome = $grossProfit + $incomes;

        return $totalIncome - $expenses;
    }

    public function openingStockBalance($specificDate)
    {
        // Step 1: Calculate initial stock for each product
        $products = Product::all();

        // Step 2: Calculate total quantities of each product in transaction details
        $totalSaleDetailQuantities = SaleDetail::whereHas('sale', function ($query) use ($specificDate) {
            $query->where('date', '<', $specificDate);
        })->groupBy('product_id')->selectRaw('product_id, sum(quantity) as total_quantity')->pluck('total_quantity', 'product_id');

        $totalSaleReturnDetailQuantities = SaleReturnDetail::whereHas('saleReturn', function ($query) use ($specificDate) {
            $query->where('date', '<', $specificDate);
        })->groupBy('product_id')->selectRaw('product_id, sum(quantity) as total_quantity')->pluck('total_quantity', 'product_id');

        $totalPurchaseDetailQuantities = PurchaseDetail::whereHas('purchase', function ($query) use ($specificDate) {
            $query->where('date', '<', $specificDate);
        })->groupBy('product_id')->selectRaw('product_id, sum(quantity) as total_quantity')->pluck('total_quantity', 'product_id');

        $totalPurchaseReturnDetailQuantities = PurchaseReturnDetail::whereHas('purchaseReturn', function ($query) use ($specificDate) {
            $query->where('date', '<', $specificDate);
        })->groupBy('product_id')->selectRaw('product_id, sum(quantity) as total_quantity')->pluck('total_quantity', 'product_id');

        // Step 3: Subtract total quantities of sales and returns from initial stock
        $openingStock = $products->map(function ($product) use ($totalSaleDetailQuantities, $totalSaleReturnDetailQuantities, $specificDate) {
            $initialStock = $product->initial_stock;
            $totalSaleQuantity = $totalSaleDetailQuantities->get($product->id, 0);
            $totalSaleReturnQuantity = $totalSaleReturnDetailQuantities->get($product->id, 0);
            return [
                'product' => $product,
                'adjustedQuantity' => $initialStock - $totalSaleQuantity + $totalSaleReturnQuantity
            ];
        });

        // Step 4: Add total quantities of purchases and returns
        $openingStock = $openingStock->map(function ($item) use ($totalPurchaseDetailQuantities, $totalPurchaseReturnDetailQuantities, $specificDate) {
            $product = $item['product'];
            $totalPurchaseQuantity = $totalPurchaseDetailQuantities->get($product->id, 0);
            $totalPurchaseReturnQuantity = $totalPurchaseReturnDetailQuantities->get($product->id, 0);
            $item['adjustedQuantity'] += $totalPurchaseQuantity - $totalPurchaseReturnQuantity;
            return $item;
        });

        return $openingStock->sum(function ($item) {
            return $item['adjustedQuantity'] * $item['product']->price_rate;
        });
    }

    public function closingStockBalance($currentDate)
    {
        // Step 1: Calculate initial stock for each product
        $products = Product::all();

        // Step 2: Calculate total quantities of each product in transaction details
        $totalSaleDetailQuantities = SaleDetail::whereHas('sale', function ($query) use ($currentDate) {
            $query->where('date', '<=', $currentDate);
        })->groupBy('product_id')->selectRaw('product_id, sum(quantity) as total_quantity')->pluck('total_quantity', 'product_id');

        $totalSaleReturnDetailQuantities = SaleReturnDetail::whereHas('saleReturn', function ($query) use ($currentDate) {
            $query->where('date', '<=', $currentDate);
        })->groupBy('product_id')->selectRaw('product_id, sum(quantity) as total_quantity')->pluck('total_quantity', 'product_id');

        $totalPurchaseDetailQuantities = PurchaseDetail::whereHas('purchase', function ($query) use ($currentDate) {
            $query->where('date', '<=', $currentDate);
        })->groupBy('product_id')->selectRaw('product_id, sum(quantity) as total_quantity')->pluck('total_quantity', 'product_id');

        $totalPurchaseReturnDetailQuantities = PurchaseReturnDetail::whereHas('purchaseReturn', function ($query) use ($currentDate) {
            $query->where('date', '<=', $currentDate);
        })->groupBy('product_id')->selectRaw('product_id, sum(quantity) as total_quantity')->pluck('total_quantity', 'product_id');

        // Step 3: Calculate adjusted quantities based on initial stock and transactions
        $closingStock = $products->map(function ($product) use ($totalSaleDetailQuantities, $totalSaleReturnDetailQuantities, $totalPurchaseDetailQuantities, $totalPurchaseReturnDetailQuantities) {
            $initialStock = $product->initial_stock;
            $totalSaleQuantity = $totalSaleDetailQuantities->get($product->id, 0);
            $totalSaleReturnQuantity = $totalSaleReturnDetailQuantities->get($product->id, 0);
            $totalPurchaseQuantity = $totalPurchaseDetailQuantities->get($product->id, 0);
            $totalPurchaseReturnQuantity = $totalPurchaseReturnDetailQuantities->get($product->id, 0);
            $adjustedQuantity = $initialStock + $totalPurchaseQuantity - $totalPurchaseReturnQuantity - $totalSaleQuantity + $totalSaleReturnQuantity;
            return [
                'product' => $product,
                'adjustedQuantity' => $adjustedQuantity
            ];
        });

        return $closingStock->sum(function ($item) {
            return $item['adjustedQuantity'] * $item['product']->price_rate;
        });
    }

    public function profitLoss(Request $request)
    {
        $date1 = $request->input('date1', '2010-01-01');
        $date2 = $request->input('date2', date('Y-m-d'));
        $expense = Expense::whereBetween('date',[$date1, $date2])->sum('amount');
        $loanInterest = Transaction::where('transaction_type','loan_interest')
            ->whereBetween('date',[$date1, $date2])->sum('amount');

        $sales = Sale::whereBetween('date', [$date1, $date2])->sum('total');
        $saleReturns = SaleReturn::whereBetween('date', [$date1, $date2])->sum('total');

        $openingStockBalance = $this->openingStockBalance($date1);
        $closingStockBalance = $this->closingStockBalance($date2);

        $purchases = Purchase::whereBetween('date', [$date1, $date2])->sum('total');

        $total1 = $openingStockBalance + $purchases - $closingStockBalance;
        $total2 = $sales - $saleReturns;
        $profit = $total2 - $total1;
        $totalExpense = $expense + $loanInterest;

        return $profit - $totalExpense;
    }
}
