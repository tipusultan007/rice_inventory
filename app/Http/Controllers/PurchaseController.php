<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\PurchaseReturn;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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

        /* $allPurchases = Purchase::all();
         foreach ($allPurchases as $purchase)
         {
             foreach ($purchase->purchaseDetails as $product) {

                 // Update product quantity
                 $productModel = Product::find($product['product_id']);
                 $productModel->quantity += $product['quantity'];
                 $productModel->price_rate = $product['price_rate'];
                 $productModel->save();
             }


             // Create a purchase transaction
             Transaction::create([
                 'amount' => $purchase->total,
                 'type' => 'credit',
                 'reference_id' => $purchase->id,
                 'transaction_type' => 'purchase',
                 'supplier_id' => $purchase->supplier_id,
                 'date' => $purchase->date,
             ]);

             $paid = $purchase->paid + $purchase->carrying_cost;
             $remain = $purchase->total - $paid;
             // If there is a payment, create a due payment transaction
             if ($paid>0) {
                 $transaction = Transaction::create([
                     'account_id' => 1, // Adjust based on your structure
                     'amount' => $paid,
                     'type' => 'debit',
                     'reference_id' => $purchase->id,
                     'transaction_type' => 'supplier_payment',
                     'supplier_id' => $purchase->supplier_id,
                     'date' => $purchase->date,
                 ]);

                 if ($purchase->carrying_cost>0){
                     $transaction->note = 'গাড়ি ভাড়া - '.$purchase->carrying_cost;
                     $transaction->save();
                 }
             }
             if ($remain>0) {
                 Transaction::create([
                     'amount' => $remain,
                     'type' => 'debit',
                     'reference_id' => $purchase->id,
                     'transaction_type' => 'purchase',
                     'supplier_id' => $purchase->supplier_id,
                     'date' => $purchase->date,
                 ]);
             }
         }*/

        /*// Retrieve purchases without invoice numbers
        $purchases = Purchase::whereNull('invoice_no')->get();

        // Start the counter from 1
        $counter = 1;

        // Generate and assign invoice numbers
        foreach ($purchases as $purchase) {
            // Generate invoice number based on current timestamp and counter
            $invoiceNumber = $this->generateUniqueInvoiceNumber($counter);

            // Assign the generated invoice number to the purchase
            $purchase->invoice_no = $invoiceNumber;
            $purchase->save();

            // Increment the counter
            $counter++;
        }*/
        $purchases = Purchase::with('purchaseDetails')->orderByDesc('id')->paginate(10);

        $firstEntry = Purchase::orderBy('date','asc')->first();

        return view('purchase.index', compact('purchases','firstEntry'))
            ->with('i', (request()->input('page', 1) - 1) * $purchases->perPage());
    }

    function generateUniqueInvoiceNumber($counter)
    {
        // Generate an invoice number based on the current timestamp and the counter
        return 'PUR-' . date('Ymd') . '-' . str_pad($counter, 6, '0', STR_PAD_LEFT);
    }

    public function dataPurchases(Request $request)
    {
        $columns = array(
            0 => 'date',
            1 => 'invoice_no',
            2 => 'supplier_id',
        );

        $date1 = $request->input('date1');
        $date2 = $request->input('date2');

        $query = Purchase::query();

        if (!empty($date1) && !empty($date2)) {
            $query->whereBetween('date', [$date1, $date2]);
        }

        $totalData = $query->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $query = Purchase::with('supplier', 'purchaseDetails');

        if (!empty($date1) && !empty($date2)) {
            $query->whereBetween('date', [$date1, $date2]);
        }

        if (empty($request->input('search.value'))) {
            $posts = $query->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $posts = $query->where('invoice_no', 'LIKE', "%{$search}%")
                ->orWhereHas('supplier', function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%{$search}%");
                })->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = $query->where('invoice_no', 'LIKE', "%{$search}%")
                ->orWhereHas('supplier', function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%{$search}%");
                })->count();
        }

        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $nestedData['id'] = $post->id;
                $nestedData['date'] = date('d/m/Y', strtotime($post->date));
                $nestedData['invoice_no'] = $post->invoice_no ?? '-';
                $nestedData['name'] = $post->supplier->name . '-' . $post->supplier->address ?? '';
                $nestedData['quantity'] = $post->purchaseDetails->sum('quantity');
                $nestedData['total'] = $post->total;
                $nestedData['paid'] = $post->paid ?? '-';
                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        return response()->json($json_data);
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
        return view('purchase.create', compact('purchase', 'products', 'suppliers', 'accounts'));
    }

    private function generateInvoiceNumber()
    {
        return str_pad(Purchase::count() + 1, 7, '0', STR_PAD_LEFT);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

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
            $trxId = Str::uuid();
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
                'trx_id' => $trxId,
                'paid' => $request->input('paid') + $request->input('carrying_cost'),
            ]);

            foreach ($request->input('products') as $product) {
                $purchaseDetail = PurchaseDetail::create([
                    'weight' => $product['weight'],
                    'purchase_id' => $purchase->id,
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                    'amount' => $product['amount'],
                    'price_rate' => $product['price_rate'],
                ]);

                $productModel = Product::find($product['product_id']);
                $productModel->quantity += $product['quantity'];
                $productModel->price_rate = $product['price_rate'];
                $productModel->save();
            }

            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $fileName = time() . '_' . $file->getClientOriginalName();

                $file->storeAs('public/purchase_attachments', $fileName);

                $purchase->attachment = $fileName;
                $purchase->save();
            }
            $debitTransaction = Transaction::create([
                'account_name' => 'ক্রয়',
                'supplier_id' => $purchase->supplier_id,
                'amount' => $purchase->total,
                'type' => 'debit',
                'reference_id' => $purchase->id,
                'transaction_type' => 'purchase',
                'date' => $purchase->date,
                'user_id' => $purchase->user_id,
                'trx_id' => $purchase->trx_id,
            ]);

            $debitTransaction->balance = $purchase->supplier->remaining_due;
            $debitTransaction->save();

            if ($purchase->paid>0) {
                $carryingCost = '';
                if ($request->input('carrying_cost')>0){
                    $carryingCost = 'গাড়ি ভাড়াঃ '.$request->input('carrying_cost');
                }
                $account = Account::find($request->account_id);
                $creditTransaction = Transaction::create([
                    'account_id' => $request->account_id,
                    'account_name' => $account->name,
                    'supplier_id' => $purchase->supplier_id,
                    'amount' => $purchase->paid,
                    'type' => 'credit',
                    'reference_id' => $purchase->id,
                    'transaction_type' => 'supplier_payment',
                    'date' => $purchase->date,
                    'user_id' => $purchase->user_id,
                    'trx_id' => $purchase->trx_id,
                    'note' => $carryingCost
                ]);
                $creditTransaction->balance = $purchase->supplier->remaining_due;
                $creditTransaction->save();
            }
            $remainingDue = $purchase->total - $purchase->paid;

            if ($remainingDue > 0){
                $creditTransaction = Transaction::create([
                    'account_name' => $purchase->supplier->name,
                    'supplier_id' => $purchase->supplier_id,
                    'amount' => $remainingDue,
                    'type' => 'credit',
                    'reference_id' => $purchase->id,
                    'transaction_type' => 'supplier_due',
                    'date' => $purchase->date,
                    'user_id' => $purchase->user_id,
                    'trx_id' => $purchase->trx_id,
                ]);
            }
            if ($request->input('tohori')>0){

                $debitTransaction1 = Transaction::create([
                    'account_name' => $purchase->supplier->name,
                    'amount' => $purchase->tohori,
                    'type' => 'debit',
                    'transaction_type' => 'supplier',
                    'reference_id' => $purchase->id,
                    'trx_id' => $purchase->trx_id,
                    'supplier_id' => $purchase->supplier_id,
                    'date' => $purchase->date,
                    'user_id' => Auth::id(),
                ]);

                $creditTransaction1 = Transaction::create([
                    'account_name' => $purchase->supplier->name,
                    'supplier_id' => $purchase->supplier_id,
                    'account_id' => 13,
                    'amount' => $purchase->tohori,
                    'type' => 'credit',
                    'reference_id' => $purchase->id,
                    'transaction_type' => 'tohori',
                    'date' => $purchase->date,
                    'user_id' => $purchase->user_id,
                    'trx_id' => $purchase->trx_id,
                ]);
                $creditTransaction1->balance = $purchase->supplier->remaining_due;
                $creditTransaction1->save();
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
     * @param int $id
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
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $purchase = Purchase::with('purchaseDetails')->findOrFail($id);
        $suppliers = Supplier::all();
        $users = User::all();
        $products = Product::all();
        $payment = Transaction::where('reference_id', $purchase->id)
            ->where('transaction_type', 'supplier_payment')
            ->where('supplier_id', $purchase->supplier_id)
            ->first();

        return view('purchase.edit', compact('purchase', 'suppliers', 'users', 'products', 'payment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Purchase $purchase
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
            $debitTransaction = Transaction::where('trx_id', $purchase->trx_id)
                ->where('transaction_type', 'purchase')
                ->where('type', 'debit')
                ->first();

            if ($debitTransaction) {
                $debitTransaction->update([
                    'amount' => $request->input('total'),
                    'supplier_id' => $request->input('supplier_id'),
                    'date' => $purchase->date,
                ]);
                $debitTransaction->balance = $purchase->supplier->remaining_due;
                $debitTransaction->save();
            }

            $creditTransaction = Transaction::where('trx_id', $purchase->trx_id)
                ->where('transaction_type', 'supplier_payment')
                ->where('type', 'credit')
                ->where('supplier_id', $purchase->supplier_id)
                ->first();

            if ($creditTransaction) {
                // If the new request has a paid amount, update the existing transaction
                if ($request->input('paid') || $request->input('carrying_cost')) {
                    $account = Account::find($request->input('account_id'));
                    $creditTransaction->update([
                        'amount' => $request->input('paid') + $request->input('carrying_cost'),
                        'date' => $purchase->date,
                        'account_id' => $request->input('account_id'),
                        'account_name' => $account->name,
                        'cheque_no' => $request->input('cheque_no'),
                        'cheque_details' => $request->input('cheque_details'),
                    ]);

                    $creditTransaction->balance = $purchase->supplier->remaining_due;
                    $creditTransaction->save();
                } else {
                    // If the new request doesn't have a paid amount, delete the existing transaction
                    $creditTransaction->delete();
                }
            } else {
                if ($request->input('paid') || $request->input('carrying_cost')) {
                    $account = Account::find($request->input('account_id'));

                    $creditTransaction = Transaction::create([
                        'account_id' => $request->input('account_id'),
                        'account_name' => $account->name,
                        'amount' => $purchase->paid,
                        'type' => 'credit',
                        'trx_id' => $purchase->trx_id,
                        'transaction_type' => 'supplier_payment',
                        'supplier_id' => $purchase->supplier_id,
                        'date' => $purchase->date,
                        'cheque_no' => $request->input('cheque_no'),
                        'cheque_details' => $request->input('cheque_details'),
                    ]);
                    $creditTransaction->balance = $purchase->supplier->remaining_due;
                    $creditTransaction->save();
                }
            }

            $paid = $request->input('paid') + $request->input('carrying_cost');
            $remain = $request->input('total') - $paid;

            $creditTransaction1 = Transaction::where('transaction_type', 'supplier_due')
                ->where('type', 'credit')
                ->where('trx_id', $purchase->trx_id)->first();

            if ($creditTransaction1) {
                if ($remain > 0) {
                    $creditTransaction1->amount = $remain;
                    $creditTransaction1->date = $purchase->date;
                    $creditTransaction1->save();
                } else {
                    $creditTransaction1->delete();
                }
            } else {
                if ($remain > 0) {
                    Transaction::create([
                        'account_name' => $purchase->supplier->name,
                        'supplier_id' => $purchase->supplier_id,
                        'amount' => $remain,
                        'type' => 'credit',
                        'reference_id' => $purchase->id,
                        'transaction_type' => 'supplier_due',
                        'date' => $purchase->date,
                        'user_id' => $purchase->user_id,
                        'trx_id' => $purchase->trx_id,
                    ]);
                }
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

        DB::beginTransaction();

        try {
            Transaction::where('trx_id', $purchase->trx_id)->delete();

            $purchaseDetails = PurchaseDetail::where('purchase_id', $purchase->id)->get();

            foreach ($purchaseDetails as $purchaseDetail) {
                $product = Product::find($purchaseDetail->product_id);
                if ($product) {
                    $product->quantity -= $purchaseDetail->quantity;
                    $product->save();
                }

                $purchaseDetail->delete();
            }
            $purchaseReturn = PurchaseReturn::where('purchase_id', $purchase->id)->first();
            if ($purchaseReturn) {
                foreach ($purchaseReturn->purchaseReturnDetail as $item) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        $product->quantity += $item->quantity;
                        $product->save();
                    }

                    $item->delete();
                }

                if ($purchaseReturn->attachment) {
                    Storage::delete('public/purchase_return_attachments/' . $purchaseReturn->attachment);
                }

                Transaction::where('trx_id', $purchaseReturn->trx_id)->delete();
                $purchaseReturn->delete();
            }

            if ($purchase->attachment) {
                Storage::delete('public/purchase_attachments/' . $purchase->attachment);
            }
            $purchase->delete();

            DB::commit();

            return response()->json(['success', 'ক্রয় এন্ট্রি সফলভাবে মোছা হয়েছে!'], 200);
        } catch (\Exception $e) {

            DB::rollBack();

            // Log the exception
            \Log::error($e);

            return response()->json(['error', 'ক্রয় এন্ট্রি মোছার সময় একটি ত্রুটি হয়েছে। আবার চেষ্টা করুন।'], 200);
        }
    }

}
