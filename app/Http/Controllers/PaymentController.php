<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class PaymentController
 * @package App\Http\Controllers
 */
class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments = Payment::with('customer','supplier')->orderByDesc('id')->paginate(10);
        $methods = PaymentMethod::all();
        $customers = Customer::all();
        $suppliers = Supplier::all();

        return view('payment.index', compact('payments','methods','customers','suppliers'))
            ->with('i', (request()->input('page', 1) - 1) * $payments->perPage());
    }
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
                'user_id' => Auth::id(),
                'payment_method_id' => $request->payment_method_id,
                'cheque_no' => $request->input('cheque_no'),
                'note' => $request->input('cheque_details'),
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
                'cheque_no' => $request->input('cheque_no'),
                'note' => $request->input('cheque_details'),
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Payment failed. Please try again.');
        }

        return redirect()->back()->with('success', 'Payment successful!');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $payment = new Payment();
        return view('payment.create', compact('payment'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Payment::$rules);

        $payment = Payment::create($request->all());

        return redirect()->route('payments.index')
            ->with('success', 'Payment created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $payment = Payment::find($id);

        return view('payment.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $payment = Payment::find($id);
        $methods = PaymentMethod::all();

        return view('payment.edit', compact('payment','methods'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Payment $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $payment)
    {
        request()->validate(Payment::$rules);

        $payment->update($request->all());

        return redirect()->route('payments.index')
            ->with('success', 'Payment updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $payment = Payment::find($id)->delete();

        return redirect()->route('payments.index')
            ->with('success', 'Payment deleted successfully');
    }
}
