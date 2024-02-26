<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function dailyReport(Request $request)
    {
        $sales = Sale::with('saleDetails','customer')
            /*->where('date',$request->date)*/
            ->orderByDesc('id')
            ->get();
        $purchases = Purchase::with('purchaseDetails','supplier')
            /*->where('date',$request->date)*/
            ->orderByDesc('id')
            ->get();

        $supplierPayments = Payment::whereNotNull('supplier_id')
            ->where('type','debit')
            /*->where('date',$request->date)*/
            ->orderByDesc('id')
            ->get();

        $customerPayments = Payment::whereNotNull('customer_id')
            ->where('type','credit')
            /*->where('date',$request->date)*/
            ->orderByDesc('id')
            ->get();

        $products25 = Product::where('quantity','>',0)->where('type','25')->get();
        $products50 = Product::where('quantity','>',0)->where('type','50')->get();

        return view('reports.daily',compact('sales','purchases','supplierPayments','customerPayments','products25','products50'));
    }

    public function paymentReport(Request $request)
    {
        $methods = PaymentMethod::all();
        $customers = Customer::all();
        $suppliers = Supplier::all();

        // Get filter parameters from the request
        $customer_id = $request->input('customer_id');
        $supplier_id = $request->input('supplier_id');
        $payment_method_id = $request->input('payment_method_id');

        // Use the filterPayments method from the previous example
        $filteredPayments = $this->filterPayments($customer_id, $supplier_id, $payment_method_id);

        // Paginate the results with a specified number of items per page
        $perPage = 10;  // You can adjust this based on your requirements
        $payments = $filteredPayments->paginate($perPage);

        return view('reports.payment',compact('payments','customers','suppliers','methods','customer_id','supplier_id','payment_method_id'));
    }

    private function filterPayments($customer_id = null, $supplier_id = null, $payment_method_id = null)
    {
        $query = Payment::query()->orderByDesc('created_at');

        if ($customer_id !== null) {
            $query->where('customer_id', $customer_id);
        }

        if ($supplier_id !== null) {
            $query->where('supplier_id', $supplier_id);
        }

        if ($payment_method_id !== null) {
            $query->where('payment_method_id', $payment_method_id);
        }

        return $query;
    }
}
