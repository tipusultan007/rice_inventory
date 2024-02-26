<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

/**
 * Class SaleController
 * @package App\Http\Controllers
 */
class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sales = Sale::with('saleDetails')->orderByDesc('id')->paginate(10);

        return view('sale.index', compact('sales'))
            ->with('i', (request()->input('page', 1) - 1) * $sales->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sale = new Sale();
        $customers = Customer::all();
        $products = Product::all();
        $users = User::all();
        $lastSale = Sale::latest()->first();
        return view('sale.create', compact('sale','customers','products','users','lastSale'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'user_id' => 'required|exists:users,id',
            'total' => 'required|numeric',
            'paid' => 'nullable|numeric',
            'note' => 'nullable|string',
            'products.*.product_id' => 'required|exists:products,id', // Assuming 'products' is the table name
            'products.*.quantity' => 'required|numeric|min:1',
            'products.*.amount' => 'required|numeric|min:0',
            'products.*.price_rate' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Start a database transaction
        DB::beginTransaction();

        try {
            $customeId = $request->customer_id;
            if ($request->customer_id === 'new'){
                $customer = Customer::create([
                    'name' => $request->input('name'),
                    'phone' => $request->input('phone'),
                    'address' => $request->input('address'),
                ]);
                $customeId = $customer->id;
            }
            // Create the purchase
            $sale = Sale::create([
                'date' => $request->input('date'),
                'book_no' => $request->input('book_no'),
                'customer_id' => $customeId,
                'user_id' => $request->input('user_id'),
                'total' => $request->input('total'),
                'invoice_no' => $request->input('invoice_no'),
                'note' => $request->input('note'),
                'subtotal' => $request->input('subtotal'),
                'discount' => $request->input('discount'),
                'dholai' => $request->input('dholai'),
            ]);

            // Create purchase details and update product quantities
            foreach ($request->input('products') as $product) {
                $saleDetail = SaleDetail::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                    'amount' => $product['amount'],
                    'price_rate' => $product['price_rate'],
                ]);

                // Update product quantity
                $product = Product::find($product['product_id']);
                $product->quantity -= $product['quantity'];
                $product->save();
            }

            $creditPayment = Payment::create([
                'customer_id' => $sale->customer_id,
                'amount' => $sale->total,
                'type' => 'debit',
                'invoice' => $sale->invoice_no,
                'user_id' => Auth::id(),
                'date' => $sale->date,
            ]);

            if ($request->paid > 0) {
                $debitPayment = Payment::create([
                    'customer_id' => $sale->customer_id,
                    'amount' => $request->paid,
                    'type' => 'credit',
                    'invoice' => $sale->invoice_no,
                    'user_id' => Auth::id(),
                    'payment_method_id' => $request->input('payment_method_id'),
                    'date' => $sale->date,
                    'cheque_no' => $request->input('cheque_no'),
                    'note' => $request->input('cheque_details'),
                ]);
            }

            // Commit the transaction
            DB::commit();

            return redirect()->route('sales.index')->with('success', 'Sale created successfully.');
        } catch (\Exception $e) {
            // Rollback the transaction in case of an exception
            DB::rollBack();

            // Log the exception
            \Log::error($e);

            return redirect()->back()->with('error', 'An error occurred while creating the sale. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sale = Sale::find($id);

        return view('sale.show', compact('sale'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sale = Sale::find($id);
        $customers = Customer::all();
        $products = Product::all();
        return view('sale.edit', compact('sale','customers','products'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Sale $sale
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
            'customer_id' => 'required|exists:customers,id',
            'user_id' => 'required|exists:users,id',
            'total' => 'required|numeric',
            'products.*.quantity' => 'required|numeric',
            'products.*.amount' => 'required|numeric',
            'products.*.product_id' => 'required|exists:products,id',
        ]);

        try {
            DB::beginTransaction();

            $sale = Sale::findOrFail($id);
            $sale->update([
                'date' => $request->input('date'),
                'book_no' => $request->input('book_no'),
                'customer_id' => $request->input('customer_id'),
                'user_id' => $request->input('user_id'),
                'total' => $request->input('total'),
                'note' => $request->input('note'),
                'subtotal' => $request->input('subtotal'),
                'discount' => $request->input('discount'),
                'dholai' => $request->input('dholai'),
            ]);

            foreach ($sale->saleDetails as $detail) {
                Product::where('id', $detail->product_id)->decrement('quantity', $detail->quantity);
            }
            $sale->saleDetails()->delete();

            foreach ($request->input('products') as $product) {
                SaleDetail::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                    'amount' => $product['amount'],
                    'price_rate' => $product['price_rate'],
                ]);
                // Update product quantity (you need to have a `quantity` field in the `products` table)
                Product::where('id', $product['product_id'])->increment('quantity', $product['quantity']);
            }

            $payment = Payment::whereNotNull('customer_id')
                ->where('type','debit')
                ->where('invoice', $sale->invoice_id)
                ->first();

            $payment->date = $sale->date;
            $payment->amount = $sale->total;
            $payment->save();

            DB::commit();

            return redirect()->route('sales.index')->with('success', 'Sale updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Error updating sale: ' . $e->getMessage())->withInput();
        }
        return redirect()->route('sales.index')
            ->with('success', 'Sale updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Sale $sale)
    {
        foreach ($sale->saleDetails as $detail) {
            Product::where('id', $detail->product_id)->increment('quantity', $detail->quantity);
        }
        $sale->saleDetails()->delete();
        Payment::whereNotNull('customer_id')->where('invoice', $sale->invoice_no)->delete();
        $sale->delete();

        return redirect()->route('sales.index')
            ->with('success', 'Sale deleted successfully');
    }
}
