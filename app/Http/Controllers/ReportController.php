<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
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
}
