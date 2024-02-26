<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        return view('purchase.create', compact('purchase','products','suppliers'));
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
            'supplier_id' => 'required|exists:suppliers,id', // Assuming 'suppliers' is the table name
            'user_id' => 'required|exists:users,id', // Assuming 'users' is the table name
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

        return view('purchase.edit', compact('purchase', 'suppliers', 'users', 'products'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Purchase $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
            'supplier_id' => 'required|exists:suppliers,id',
            'user_id' => 'required|exists:users,id',
            'total' => 'required|numeric',
            'additional_field' => 'nullable|string',
            'products.*.quantity' => 'required|numeric',
            'products.*.amount' => 'required|numeric',
            'products.*.product_id' => 'required|exists:products,id',
        ]);

        try {
            DB::beginTransaction();

            $purchase = Purchase::findOrFail($id);
            $purchase->update([
                'date' => $request->input('date'),
                'supplier_id' => $request->input('supplier_id'),
                'user_id' => $request->input('user_id'),
                'book_no' => $request->input('book_no'),
                'total' => $request->input('total'),
                'note' => $request->input('note'),
                'carrying_cost' => $request->input('carrying_cost'),
                'subtotal' => $request->input('subtotal'),
                'discount' => $request->input('discount'),
                'truck_no' => $request->input('truck_no'),
                'tohori' => $request->input('tohori'),
            ]);

            foreach ($purchase->purchaseDetails as $detail) {
                // Adjust the product quantity (you need to have a `quantity` field in the `products` table)
                Product::where('id', $detail->product_id)->decrement('quantity', $detail->quantity);
            }

            $purchase->purchaseDetails()->delete();

            // Add new details
            foreach ($request->input('products') as $product) {
                PurchaseDetail::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                    'amount' => $product['amount'],
                    'price_rate' => $product['price_rate'],
                ]);

                // Update product quantity (you need to have a `quantity` field in the `products` table)
                Product::where('id', $product['product_id'])->increment('quantity', $product['quantity']);
            }

            $payment = Payment::whereNotNull('customer_id')
                ->where('type','credit')
                ->where('invoice', $purchase->invoice_id)
                ->first();

           if ($payment){
               $payment->date = $purchase->date;
               $payment->amount = $purchase->total;
               $payment->save();
           }

            DB::commit();

            return redirect()->route('purchases.index')->with('success', 'Purchase updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Error updating purchase: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Purchase $purchase)
    {
        foreach ($purchase->purchaseDetails as $detail) {
            Product::where('id', $detail->product_id)->decrement('quantity', $detail->quantity);
        }
        $purchase->purchaseDetails()->delete();
        Payment::whereNotNull('supplier_id')->where('invoice', $purchase->invoice_no)->delete();
        $purchase->delete();

        return redirect()->route('purchases.index')
            ->with('success', 'Purchase deleted successfully');
    }
}
