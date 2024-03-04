<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

/**
 * Class PurchaseController
 * @package App\Http\Controllers
 */
class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $purchases = Purchase::with('purchaseDetails')->orderByDesc('id')->paginate(10);

        return view('purchase.index', compact('purchases'))
            ->with('i', (request()->input('page', 1) - 1) * $purchases->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $purchase = new Purchase();
        $products = Product::all();
        $suppliers = Supplier::all();
        $accounts = Account::all();
        return view('purchase.create', compact('purchase','products','suppliers','accounts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    /*public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'supplier_id' => 'required|exists:suppliers,id', // Assuming 'suppliers' is the table name
            'user_id' => 'required|exists:users,id', // Assuming 'users' is the table name
            'total' => 'required|numeric',
            'paid' => 'nullable|numeric',
            'note' => 'nullable|string',
            'products.*.product_id' => 'required|exists:products,id', // Assuming 'products' is the table name
            'products.*.quantity' => 'required|numeric|min:1',
            'products.*.amount' => 'required|numeric|min:0',
            'products.*.price_rate' => 'required|numeric|min:0',
            'attachment' => 'nullable|file|mimes:jpeg,png,pdf,docx,xlsx|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Create the purchase
            $purchase = Purchase::create([
                'date' => $request->input('date'),
                'supplier_id' => $request->input('supplier_id'),
                'user_id' => $request->input('user_id'),
                'total' => $request->input('total'),
                'invoice_no' => $request->input('invoice_no'),
                'note' => $request->input('note'),
                'carrying_cost' => $request->input('carrying_cost'),
                'subtotal' => $request->input('subtotal'),
                'discount' => $request->input('discount'),
                'truck_no' => $request->input('truck_no'),
                'tohori' => $request->input('tohori'),
            ]);

            // Create purchase details and update product quantities
            foreach ($request->input('products') as $product) {
                $purchaseDetail = PurchaseDetail::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                    'amount' => $product['amount'],
                    'price_rate' => $product['price_rate'],
                    'weight' => $product['weight'],
                ]);

                // Update product quantity
                $productModel = Product::find($product['product_id']);
                $productModel->quantity += $product['quantity'];
                $productModel->save();
            }

            $creditPayment = Payment::create([
                'supplier_id' => $purchase->supplier_id,
                'amount' => $purchase->total,
                'type' => 'credit',
                'invoice' => $purchase->invoice_no,
                'user_id' => Auth::id(),
                'date' => $purchase->date,
            ]);

            if ($request->paid>0 || $request->carrying_cost) {
                $carryingCost = '';
                if ($request->carrying_cost>0){
                    $carryingCost = '<p>গাড়ি ভাড়াঃ '.$request->carrying_cost.'</p>';
                }
                $debitPayment = Payment::create([
                    'supplier_id' => $purchase->supplier_id,
                    'amount' => $request->input('paid',0) + $request->input('carrying_cost',0),
                    'type' => 'debit',
                    'invoice' => $purchase->invoice_no,
                    'user_id' => Auth::id(),
                    'payment_method_id' => $request->input('payment_method_id'),
                    'date' => $purchase->date,
                    'cheque_no' => $request->input('cheque_no'),
                    'note' => $carryingCost.' '.$request->input('cheque_details'),
                ]);
            }

            if ($request->hasFile('attachment')) {
                $attachment = $request->file('attachment');
                $path = $attachment->store('purchase_attachments', 'public');
                $purchase->attachment = $path;
                $purchase->save();
            }

            // Commit the transaction
            DB::commit();

            return redirect()->route('purchases.create')->with('success', 'ক্রয় এন্ট্রি সফল হয়েছে!');
        } catch (\Exception $e) {
            // Rollback the transaction in case of an exception
            DB::rollBack();

            // Log the exception
            \Log::error($e);

            return redirect()->back()->with('error', 'ক্রয় এন্ট্রি ব্যর্থ হয়েছে। আবার চেষ্টা করুন।');
        }
    }*/

    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'user_id' => 'required|exists:users,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'invoice_no' => 'nullable|unique:purchases,invoice_no',
            'truck_no' => 'nullable',
            'subtotal' => 'required|numeric',
            'carrying_cost' => 'nullable|numeric',
            'tohori' => 'nullable|numeric',
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
            // Create the purchase
            $purchase = Purchase::create([
                'date' => $request->input('date'),
                'user_id' => $request->input('user_id'),
                'supplier_id' => $request->input('supplier_id'),
                'invoice_no' => $request->input('invoice_no'),
                'truck_no' => $request->input('truck_no'),
                'subtotal' => $request->input('subtotal'),
                'carrying_cost' => $request->input('carrying_cost'),
                'tohori' => $request->input('tohori'),
                'discount' => $request->input('discount'),
                'total' => $request->input('total'),
                'note' => $request->input('note'),
                'due' => $request->input('due'),
                'paid' => $request->input('paid') + $request->input('carrying_cost'),
            ]);

            // Create purchase details and update product quantities
            foreach ($request->input('products') as $product) {
                $purchaseDetail = PurchaseDetail::create([
                    'weight' => $product['weight'],
                    'purchase_id' => $purchase->id,
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                    'amount' => $product['amount'],
                    'price_rate' => $product['price_rate'],
                ]);

                // Update product quantity
                $productModel = Product::find($product['product_id']);
                $productModel->quantity += $product['quantity'];
                $productModel->price_rate = $product['price_rate'];
                $productModel->save();
            }

            // Handle file attachment
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $fileName = time() . '_' . $file->getClientOriginalName();

                $file->storeAs('public/purchase_attachments', $fileName);

                $purchase->attachment = $fileName;
                $purchase->save();
            }

            // Create a purchase transaction
            Transaction::create([
                'amount' => $request->input('total'),
                'type' => 'credit',
                'reference_id' => $purchase->id,
                'transaction_type' => 'purchase',
                'supplier_id' => $purchase->supplier_id,
                'date' => $purchase->date,
            ]);

            // If there is a payment, create a due payment transaction
            if ($request->input('paid') || $request->input('carrying_cost')) {
                Transaction::create([
                    'account_id' => $request->input('account_id'), // Adjust based on your structure
                    'amount' => $request->input('paid') + $request->input('carrying_cost'),
                    'type' => 'debit',
                    'reference_id' => $purchase->id,
                    'transaction_type' => 'supplier_payment',
                    'supplier_id' => $purchase->supplier_id,
                    'date' => $purchase->date,
                    'cheque_no' => $request->input('cheque_no'),
                    'cheque_details' => $request->input('cheque_details'),
                ]);
            }

            // Commit the transaction
            DB::commit();

            return redirect()->route('purchases.create')->with('success', 'Purchase entry successful!');
        } catch (\Exception $e) {
            // Rollback the transaction in case of an exception
            DB::rollBack();

            // Log the exception
            \Log::error($e);

            return redirect()->back()->with('error', 'Purchase entry failed. Please try again.');
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
        $purchase = Purchase::find($id);

        return view('purchase.show', compact('purchase'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $purchase = Purchase::with('purchaseDetails')->findOrFail($id);
        $suppliers = Supplier::all();
        $users = User::all();
        $products = Product::all();
       $payment =  Transaction::where('reference_id', $purchase->id)
            ->where('transaction_type', 'supplier_payment')
            ->where('supplier_id', $purchase->supplier_id)
            ->first();

        return view('purchase.edit', compact('purchase', 'suppliers', 'users', 'products','payment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Purchase $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Purchase $purchase)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'user_id' => 'required|exists:users,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'invoice_no' => 'nullable|unique:purchases,invoice_no,' . $purchase->id,
            'truck_no' => 'nullable',
            'subtotal' => 'required|numeric',
            'carrying_cost' => 'nullable|numeric',
            'tohori' => 'nullable|numeric',
            'discount' => 'nullable|numeric',
            'total' => 'required|numeric',
            'note' => 'nullable|string',
            'due' => 'nullable|numeric',
            'paid' => 'nullable|numeric',
            'attachment' => 'nullable|file|mimes:jpeg,png,pdf,docx,xlsx|max:2048', // Adjust the allowed file types and size
        ], [
            'date.required' => 'তারিখ পূর্ণ করতে হবে।',
            'date.date' => 'তারিখ সঠিক নয়।',
            'user_id.required' => 'ব্যবহারকারী নির্বাচন করতে হবে।',
            'user_id.exists' => 'ব্যবহারকারী সঠিক নয়।',
            // Add more custom error messages as needed
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Update the purchase
            $purchase->update([
                'date' => $request->input('date'),
                'user_id' => $request->input('user_id'),
                'supplier_id' => $request->input('supplier_id'),
                'invoice_no' => $request->input('invoice_no'),
                'truck_no' => $request->input('truck_no'),
                'subtotal' => $request->input('subtotal'),
                'carrying_cost' => $request->input('carrying_cost'),
                'tohori' => $request->input('tohori'),
                'discount' => $request->input('discount'),
                'total' => $request->input('total'),
                'note' => $request->input('note'),
                'due' => $request->input('due'),
                'paid' => $request->input('paid') + $request->input('carrying_cost'),
            ]);

            // Update purchase details and product quantities
            PurchaseDetail::where('purchase_id', $purchase->id)->delete();

            foreach ($request->input('products') as $product) {
                $purchaseDetail = PurchaseDetail::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                    'amount' => $product['amount'],
                    'price_rate' => $product['price_rate'],
                ]);

                // Update product quantity
                $productModel = Product::find($product['product_id']);
                $productModel->quantity += $product['quantity'];
                $productModel->price_rate = $product['price_rate'];
                $productModel->save();
            }

            // Handle file attachment
            if ($request->hasFile('attachment')) {
                // Delete old attachment
                $oldAttachment = $purchase->attachment;
                if ($oldAttachment) {
                    Storage::delete('public/purchase_attachments/' . $oldAttachment);
                }

                // Upload new attachment
                $file = $request->file('attachment');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/purchase_attachments', $fileName);

                // Update purchase with new attachment
                $purchase->attachment = $fileName;
                $purchase->save();
            }

            // Update the purchase transaction
            $purchaseTransaction = Transaction::where('reference_id', $purchase->id)
                ->where('transaction_type', 'purchase')
                ->where('supplier_id', $purchase->supplier_id)
                ->first();

            if ($purchaseTransaction) {
                $purchaseTransaction->update([
                    'amount' => $request->input('total'),
                    'date' => $purchase->date,
                ]);
            }

            $existingSupplierPaymentTransaction = Transaction::where('reference_id', $purchase->id)
                ->where('transaction_type', 'supplier_payment')
                ->where('supplier_id', $purchase->supplier_id)
                ->first();

            if ($existingSupplierPaymentTransaction) {
                // If the new request has a paid amount, update the existing transaction
                if ($request->input('paid') || $request->input('carrying_cost')) {
                    $existingSupplierPaymentTransaction->update([
                        'amount' => $request->input('paid') + $request->input('carrying_cost'),
                        'date' => $purchase->date,
                        'account_id' => $request->input('account_id'),
                        'cheque_no' => $request->input('cheque_no'),
                        'cheque_details' => $request->input('cheque_details'),
                    ]);
                } else {
                    // If the new request doesn't have a paid amount, delete the existing transaction
                    $existingSupplierPaymentTransaction->delete();
                }
            } elseif ($request->input('paid') || $request->input('carrying_cost')) {
                // If there wasn't an existing transaction, and the new request has a paid amount, create a new transaction
                Transaction::create([
                    'account_id' => $request->input('account_id'), // Adjust based on your structure
                    'amount' => $request->input('paid') + $request->input('carrying_cost'),
                    'type' => 'debit',
                    'reference_id' => $purchase->id,
                    'transaction_type' => 'supplier_payment',
                    'supplier_id' => $purchase->supplier_id,
                    'date' => $purchase->date,
                    'cheque_no' => $request->input('cheque_no'),
                    'cheque_details' => $request->input('cheque_details'),
                ]);
            }


            // Commit the transaction
            DB::commit();

            return redirect()->route('purchases.edit', $purchase->id)->with('success', 'ক্রয় এন্ট্রি আপডেট সফল হয়েছে!');
        } catch (\Exception $e) {
            // Rollback the transaction in case of an exception
            DB::rollBack();

            // Log the exception
            \Log::error($e);

            return redirect()->back()->with('error', 'ক্রয় এন্ট্রি আপডেট ব্যর্থ হয়েছে। আবার চেষ্টা করুন।');
        }
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Purchase $purchase)
    {
        // Start a database transaction
        DB::beginTransaction();

        try {
            // Delete the related supplier payment transaction
            Transaction::where('reference_id', $purchase->id)
                ->where('transaction_type', 'supplier_payment')
                ->where('supplier_id', $purchase->supplier_id)
                ->delete();

            Transaction::where('reference_id', $purchase->id)
                ->where('transaction_type', 'purchase')
                ->where('supplier_id', $purchase->supplier_id)
                ->delete();

            // Get the related purchase details
            $purchaseDetails = PurchaseDetail::where('purchase_id', $purchase->id)->get();

            // Decrement product quantities
            foreach ($purchaseDetails as $purchaseDetail) {
                $product = Product::find($purchaseDetail->product_id);
                if ($product) {
                    $product->quantity -= $purchaseDetail->quantity;
                    $product->save();
                }

                $purchaseDetail->delete();
            }

            // Delete the attachment
            if ($purchase->attachment) {
                Storage::delete('public/purchase_attachments/' . $purchase->attachment);
            }

            // Delete the purchase and its details
            $purchase->delete();

            // Commit the transaction
            DB::commit();

            return redirect()->route('purchases.index')->with('success', 'ক্রয় এন্ট্রি সফলভাবে মোছা হয়েছে!');
        } catch (\Exception $e) {
            // Rollback the transaction in case of an exception
            DB::rollBack();

            // Log the exception
            \Log::error($e);

            return redirect()->back()->with('error', 'ক্রয় এন্ট্রি মোছার সময় একটি ত্রুটি হয়েছে। আবার চেষ্টা করুন।');
        }
    }

}
