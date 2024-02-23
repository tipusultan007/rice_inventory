<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Payment;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Constraint\Operator;

class PaymentController extends Controller
{
    public function customerPayment(Request $request)
    {
        $customer = Customer::find($request->customer_id);
        if ($customer->remainingDue < $request->amount){
            return redirect()->back()->with('error', 'Payment failed. Payment amount larger than due!');
        }
        try {
            $payment = Payment::create([
                'customer_id' => $request->customer_id,
                'amount' => $request->amount,
                'type' => 'credit',
                'date' => $request->date,
                'user_id' => Auth::id()
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Payment failed. Please try again.');
        }

        return redirect()->back()->with('success', 'Payment successful!');
    }

    public function supplierPayment(Request $request)
    {
        $supplier = Supplier::find($request->supplier_id);
        if ($supplier->remainingDue < $request->amount){
            return redirect()->back()->with('error', 'Payment failed. Payment amount larger than due!');
        }
        try {
            $payment = Payment::create([
                'supplier_id' => $request->supplier_id,
                'amount' => $request->amount,
                'type' => 'debit',
                'date' => $request->date,
                'user_id' => Auth::id(),
                'payment_method_id' => $request->payment_method_id,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Payment failed. Please try again.');
        }

        return redirect()->back()->with('success', 'Payment successful!');
    }
}
