<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Customer;
use App\Models\Due;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
        $accounts = Account::all();
        $lastSale = Sale::where('user_id', auth()->id())->latest()->first();
        return view('sale.create', compact('sale','customers','products','users','lastSale','accounts'));
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
            'customer_id' => 'required|exists:customers,id',
            'invoice_no' => 'nullable|unique:sales,invoice_no',
            'book_no' => 'nullable',
            'subtotal' => 'required|numeric',
            'dholai' => 'nullable|numeric',
            'discount' => 'nullable|numeric',
            'total' => 'required|numeric',
            'note' => 'nullable|string',
            'due' => 'nullable|numeric',
            'paid' => 'nullable|numeric',
            'attachment' => 'nullable|file|mimes:jpeg,png,pdf,docx,xlsx|max:2048', // Adjust the allowed file types and size
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        // Start a database transaction
        DB::beginTransaction();
        try {
            // Create the sale
            $sale = Sale::create([
                'date' => $request->input('date'),
                'user_id' => $request->input('user_id'),
                'customer_id' => $request->input('customer_id'),
                'invoice_no' => $request->input('invoice_no'),
                'book_no' => $request->input('book_no'),
                'subtotal' => $request->input('subtotal'),
                'dholai' => $request->input('dholai'),
                'discount' => $request->input('discount'),
                'total' => $request->input('total'),
                'note' => $request->input('note'),
                'due' => $request->input('due'),
                'paid' => $request->input('paid'),
            ]);

            // Create sale details and update product quantities
            foreach ($request->input('products') as $product) {
                SaleDetail::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                    'amount' => $product['amount'],
                    'price_rate' => $product['price_rate'],
                ]);

                // Update product quantity (assuming you have a Product model)
                $productModel = Product::find($product['product_id']);
                $productModel->quantity -= $product['quantity'];
                $productModel->save();
            }

            // Handle file attachment
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $fileName = time() . '_' . $file->getClientOriginalName();

                $file->storeAs('public/sale_attachments', $fileName);

                $sale->attachment = $fileName;
                $sale->save();
            }

            // Create a sale transaction
            Transaction::create([
                'amount' => $request->input('total'),
                'type' => 'debit',
                'reference_id' => $sale->id,
                'transaction_type' => 'sale',
                'customer_id' => $sale->customer_id,
                'date' => $sale->date,
            ]);

            // If there is a payment, create a due payment transaction
            if ($request->input('paid')) {
                Transaction::create([
                    'account_id' => $request->input('account_id'), // Adjust based on your structure
                    'amount' => $request->input('paid'),
                    'type' => 'credit',
                    'reference_id' => $sale->id,
                    'transaction_type' => 'due_payment',
                    'customer_id' => $sale->customer_id,
                    'date' => $sale->date,
                    'cheque_no' => $request->input('cheque_no'),
                    'cheque_details' => $request->input('cheque_details'),
                ]);
            }

            // Commit the transaction
            DB::commit();

        } catch (\Exception $e) {
            // Rollback the transaction in case of an exception
            DB::rollBack();

            // Log the exception
            \Log::error($e);

            return redirect()->back()->with('error', 'Sale entry failed. Please try again.');
        }

        return redirect()->route('sales.create')->with('success', 'Sale entry successful!');
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
        $payment = Transaction::where('reference_id', $sale->id)
            ->where('transaction_type', 'due_payment')
            ->where('customer_id', $sale->customer_id)
            ->first();
        return view('sale.edit', compact('sale','customers','products','payment'));
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
        // Validate the request
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'user_id' => 'required|exists:users,id',
            'customer_id' => 'required|exists:customers,id',
            'invoice_no' => 'nullable|unique:sales,invoice_no,' . $id,
            'book_no' => 'nullable',
            'subtotal' => 'required|numeric',
            'dholai' => 'nullable|numeric',
            'discount' => 'nullable|numeric',
            'total' => 'required|numeric',
            'note' => 'nullable|string',
            'due' => 'nullable|numeric',
            'paid' => 'nullable|numeric',
            'attachment' => 'nullable|file|mimes:jpeg,png,pdf,docx,xlsx|max:2048', // Adjust the allowed file types and size
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Find the sale record to update
            $sale = Sale::findOrFail($id);

            // Update the sale
            $sale->update([
                'date' => $request->input('date'),
                'user_id' => $request->input('user_id'),
                'customer_id' => $request->input('customer_id'),
                'invoice_no' => $request->input('invoice_no'),
                'book_no' => $request->input('book_no'),
                'subtotal' => $request->input('subtotal'),
                'dholai' => $request->input('dholai'),
                'discount' => $request->input('discount'),
                'total' => $request->input('total'),
                'note' => $request->input('note'),
                'due' => $request->input('due'),
                'paid' => $request->input('paid'),
            ]);

            // Delete existing sale details for this sale
            SaleDetail::where('sale_id', $id)->delete();

            // Create sale details and update product quantities
            foreach ($request->input('products') as $product) {
                SaleDetail::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                    'amount' => $product['amount'],
                    'price_rate' => $product['price_rate'],
                ]);

                // Update product quantity (assuming you have a Product model)
                $productModel = Product::find($product['product_id']);
                $productModel->quantity -= $product['quantity'];
                $productModel->save();
            }

            // Handle file attachment
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $fileName = time() . '_' . $file->getClientOriginalName();

                // Delete existing attachment file if it exists
                if ($sale->attachment) {
                    Storage::delete('public/sale_attachments/' . $sale->attachment);
                }

                // Store the new file
                $file->storeAs('public/sale_attachments', $fileName);

                $sale->attachment = $fileName;
                $sale->save();
            }

            // If there was a debit transaction before, update or delete it
            $existingDebitTransaction = Transaction::where('reference_id', $sale->id)
                ->where('transaction_type', 'sale')
                ->where('customer_id', $sale->customer_id)
                ->first();

            if ($existingDebitTransaction) {
                // If the new request has a total amount, update the existing transaction
                if ($request->input('total')) {
                    $existingDebitTransaction->update([
                        'amount' => $request->input('total'),
                        'date' => $sale->date,
                    ]);
                } else {
                    // If the new request doesn't have a total amount, delete the existing transaction
                    $existingDebitTransaction->delete();
                }
            } elseif ($request->input('total')) {
                // If there wasn't an existing transaction, and the new request has a total amount, create a new transaction
                Transaction::create([
                    'amount' => $request->input('total'),
                    'type' => 'debit',
                    'reference_id' => $sale->id,
                    'transaction_type' => 'sale',
                    'customer_id' => $sale->customer_id,
                    'date' => $sale->date,
                ]);
            }

            // If there was a paid transaction before, update or delete it
            $existingPaidTransaction = Transaction::where('reference_id', $sale->id)
                ->where('transaction_type', 'due_payment')
                ->where('customer_id', $sale->customer_id)
                ->first();

            if ($existingPaidTransaction) {
                // If the new request has a paid amount, update the existing transaction
                if ($request->input('paid')) {
                    $existingPaidTransaction->update([
                        'amount' => $request->input('paid'),
                        'date' => $sale->date,
                        'account_id' => $request->input('account_id'),
                        'cheque_no' => $request->input('cheque_no'),
                        'cheque_details' => $request->input('cheque_details'),
                    ]);
                } else {
                    // If the new request doesn't have a paid amount, delete the existing transaction
                    $existingPaidTransaction->delete();
                }
            } elseif ($request->input('paid')) {
                // If there wasn't an existing transaction, and the new request has a paid amount, create a new transaction
                Transaction::create([
                    'account_id' => $request->input('account_id'), // Adjust based on your structure
                    'amount' => $request->input('paid'),
                    'type' => 'credit',
                    'reference_id' => $sale->id,
                    'transaction_type' => 'due_payment',
                    'customer_id' => $sale->customer_id,
                    'date' => $sale->date,
                    'cheque_no' => $request->input('cheque_no'),
                    'cheque_details' => $request->input('cheque_details'),
                ]);
            }


            // Commit the transaction
            DB::commit();
        } catch (\Exception $e) {
            // Rollback the transaction in case of an exception
            DB::rollBack();

            // Log the exception
            \Log::error($e);

            return redirect()->back()->with('error', 'Sale update failed. Please try again.');
        }

        return redirect()->route('sales.index')->with('success', 'Sale update successful!');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Sale $sale)
    {
        // Start a database transaction
        DB::beginTransaction();

        try {
            // Store customer information before deleting the sale
            $customerID = $sale->customer_id;

            // Delete sale details
            $saleDetails = SaleDetail::where('sale_id', $sale->id)->get();

            foreach ($saleDetails as $saleDetail) {
                // Adjust product quantity
                $product = $saleDetail->product;
                $product->quantity += $saleDetail->quantity;
                $product->save();

                // Delete sale detail
                $saleDetail->delete();
            }

            // If there was a debit transaction, delete it
            Transaction::where('reference_id', $sale->id)
                ->where('transaction_type', 'sale')
                ->where('customer_id', $customerID)
                ->delete();

            // If there was a paid transaction, delete it
            Transaction::where('reference_id', $sale->id)
                ->where('transaction_type', 'due_payment')
                ->where('customer_id', $customerID)
                ->delete();

            // Delete the attachment
            if ($sale->attachment) {
                Storage::delete('public/sale_attachments/' . $sale->attachment);
            }

            // Delete the sale record
            $sale->delete();

            // Commit the transaction
            DB::commit();
        } catch (\Exception $e) {
            // Rollback the transaction in case of an exception
            DB::rollBack();

            // Log the exception
            \Log::error($e);

            return redirect()->back()->with('error', 'Sale deletion failed. Please try again.');
        }

        return redirect()->route('sales.index')->with('success', 'Sale deletion successful!');
    }
}
