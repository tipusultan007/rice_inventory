<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Asset;
use App\Models\Customer;
use App\Models\Loan;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Sale;
use App\Models\SaleDetail;
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
        $sales = Sale::with('saleDetails', 'customer')
            ->where('date', $date)
            ->orderByDesc('id')
            ->get();
        $purchases = Purchase::with('purchaseDetails', 'supplier')
            ->where('date', $date)
            ->orderByDesc('id')
            ->get();

        $supplierPayments = Transaction::whereNotNull('supplier_id')
            ->where('transaction_type', 'supplier_payment')
            ->where('date', $date)
            ->orderByDesc('id')
            ->get();

        $customerPayments = Transaction::whereNotNull('customer_id')
            ->where('transaction_type', 'due_payment')
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
        });

        return view('reports.daily', compact('sales', 'purchases', 'supplierPayments', 'customerPayments', 'productData25', 'productData50'));
    }

    public function balanceSheet(Request $request)
    {
        $accounts = Account::with('transactions')->get();
        $product_stock = Product::select(DB::raw('SUM(quantity) as total_quantity,SUM(quantity*price_rate) as total_price'))->first();
        $customer_due = Transaction::whereNotNull('customer_id')
            ->select(
                DB::raw('SUM(CASE WHEN type = "debit" THEN amount ELSE 0 END) as debit'),
                DB::raw('SUM(CASE WHEN type = "credit" THEN amount ELSE 0 END) as credit')
            )->first();
        $assets = Asset::sum('value');

        $supplier_due = Transaction::whereNotNull('supplier_id')
            ->select(
                DB::raw('SUM(CASE WHEN type = "debit" THEN amount ELSE 0 END) as debit'),
                DB::raw('SUM(CASE WHEN type = "credit" THEN amount ELSE 0 END) as credit')
            )->first();

        $loans = Loan::sum('balance');
        return view('reports.balance_sheet',
            compact('accounts',
            'product_stock',
            'customer_due',
            'assets',
            'supplier_due',
            'loans'
        ));
    }

    public function paymentReport(Request $request)
    {
        $methods = Account::all();
        $customers = Customer::all();
        $suppliers = Supplier::all();

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
}
