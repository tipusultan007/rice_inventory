<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productQuantities = Product::selectRaw('type, SUM(quantity) as total_quantity')
            ->whereIn('type', ['25', '50'])
            ->groupBy('type')
            ->pluck('total_quantity', 'type');

        $totalDue = $this->totalDueForAllCustomers();
        $supplierDue = $this->totalDueForAllSuppliers();


        return view('home',compact('productQuantities','totalDue','supplierDue'));
    }

    public function totalDueForAllCustomers()
    {
       /* // Calculate total payments
        $totalPayments = DB::table('transactions')
            ->where('transaction_type', 'customer_payment')
            ->where('type', 'credit')
            ->sum('amount');

        // Calculate total due
        $totalDue = DB::table('transactions')
            ->where('transaction_type', 'sale')
            ->where('type', 'debit')
            ->sum('amount');

        // Calculate total due after deducting total payments
        return $totalDue - $totalPayments;*/
        $total = DB::table('transactions')
            ->select(DB::raw('SUM(
            CASE
                WHEN transaction_type = "customer_opening_balance" AND type = "debit" THEN amount
                WHEN transaction_type = "sale" AND type = "credit" THEN amount
                WHEN transaction_type = "customer_payment" AND type = "debit" THEN -amount
                WHEN transaction_type = "discount" AND type = "debit" THEN -amount
                WHEN transaction_type = "payment_to_customer" AND type = "credit" THEN -amount
                ELSE 0
            END
        ) AS total_due'))
            ->whereNotNull('customer_id')
            ->value('total_due');

        return $total;
    }

    public function totalDueForAllSuppliers()
    {
        /*// Calculate total payments to suppliers
        $totalPayments = DB::table('transactions')
            ->where('transaction_type', 'purchase')
            ->where('type', 'credit')
            ->sum('amount');

        // Calculate total supplier payments
        $totalSupplierPayments = DB::table('transactions')
            ->where('transaction_type', 'supplier_payment')
            ->where('type', 'debit')
            ->sum('amount');

        // Calculate total due for all suppliers
        return $totalPayments - $totalSupplierPayments;*/

        $total = DB::table('transactions')
            ->select(DB::raw('SUM(
            CASE
                WHEN transaction_type = "supplier_opening_balance" AND type = "credit" THEN amount
                WHEN transaction_type = "purchase" AND type = "debit" THEN amount
                WHEN transaction_type = "supplier_payment" AND type = "credit" THEN -amount
                WHEN transaction_type = "tohori" AND type = "credit" THEN -amount
                WHEN transaction_type = "discount" AND type = "credit" THEN -amount
                WHEN transaction_type = "payment_from_supplier" AND type = "debit" THEN -amount
                ELSE 0
            END
        ) AS total_due'))
            ->whereNotNull('supplier_id')
            ->value('total_due');

        return $total;
    }
}
