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
        // Calculate total payments
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
        return $totalDue - $totalPayments;
    }

    public function totalDueForAllSuppliers()
    {
        // Calculate total payments to suppliers
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
        return $totalPayments - $totalSupplierPayments;
    }
}
