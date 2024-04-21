<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessSalesJob;
use App\Models\Account;
use App\Models\Customer;
use App\Models\Due;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\SaleReturn;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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

        $firstEntry = Sale::orderBy('date','asc')->first();

        return view('sale.index', compact('sales','firstEntry'))
            ->with('i', (request()->input('page', 1) - 1) * $sales->perPage());
    }
    public function dataSales(Request $request)
    {
        $columns = array(
            0 => 'date',
            1 => 'invoice_no',
            2 => 'customer_id',
        );
        $date1 = $request->input('date1');
        $date2 = $request->input('date2');
        if (!empty($date1) && !empty($date2)) {
            $totalData = Sale::whereBetween('date', [$date1, $date2])->count();
        }else{
            $totalData = Sale::count();
        }
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $query = Sale::with('customer', 'saleDetails');

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
                ->orWhereHas('customer', function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%{$search}%");
                })->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = $query->where('invoice_no', 'LIKE', "%{$search}%")
                ->orWhereHas('customer', function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%{$search}%");
                })->count();
        }

        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $nestedData['id'] = $post->id;
                $nestedData['date'] = date('d/m/Y', strtotime($post->date));
                $nestedData['invoice_no'] = $post->invoice_no ?? '-';
                $nestedData['name'] = $post->customer->name . '-' . $post->customer->address ?? '';
                $nestedData['quantity'] = $post->saleDetails->sum('quantity');
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

    /*public function dataSales(Request $request)
    {

        $columns = array(
            0 => 'date',
            1 => 'invoice_no',
            2 => 'customer_id',
        );

        $totalData = Sale::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts = Sale::with('customer', 'saleDetails')->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $posts = Sale::with('customer', 'saleDetails')->where('invoice_no', 'LIKE', "%{$search}%")
                ->orWhereHas('customer', function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%{$search}%");
                })->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = Sale::where('invoice_no', 'LIKE', "%{$search}%")
                ->orWhereHas('customer', function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%{$search}%");
                })->count();
        }

        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $nestedData['id'] = $post->id;
                $nestedData['date'] = date('d/m/Y', strtotime($post->date));
                $nestedData['invoice_no'] = $post->invoice_no ?? '-';
                $nestedData['name'] = $post->customer->name . '-' . $post->customer->address ?? '';
                $nestedData['quantity'] = $post->saleDetails->sum('quantity');
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

        echo json_encode($json_data);
    }*/

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sale = new Sale();
        $customers = Customer::all();
        $products = Product::orderBy('quantity','desc')->get();
        $users = User::all();
        $accounts = Account::all();
        $lastSale = Sale::where('user_id', auth()->id())->latest()->first();
        return view('sale.create', compact('sale', 'customers', 'products', 'users', 'lastSale', 'accounts'));
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
            'invoice_no' => 'nullable|unique:sales,invoice_no',
            'book_no' => 'nullable',
            'subtotal' => 'required|numeric',
            'dholai' => 'nullable|numeric',
            'discount' => 'nullable|numeric',
            'total' => 'required|numeric',
            'note' => 'nullable|string',
            'due' => 'nullable|numeric',
            'paid' => 'nullable|numeric',
            'attachment' => 'nullable|file|mimes:jpeg,png,pdf,docx,xlsx|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $customerId = $request->input('customer_id');
            if ($request->customer_id === 'new'){
                $customer = Customer::create([
                    'name' => $request->input('name'),
                    'phone' => $request->input('phone'),
                    'address' => $request->input('address'),
                ]);
            }else{
                $customer = Customer::find($request->input('customer_id'));
            }

            $trxId = Str::uuid();
            $sale = Sale::create([
                'date' => $request->input('date'),
                'user_id' => $request->input('user_id'),
                'customer_id' => $customer->id,
                'invoice_no' => $request->input('invoice_no'),
                'book_no' => $request->input('book_no'),
                'subtotal' => $request->input('subtotal'),
                'dholai' => $request->input('dholai'),
                'discount' => $request->input('discount'),
                'total' => $request->input('total'),
                'note' => $request->input('note'),
                'due' => $request->input('due'),
                'paid' => $request->input('paid'),
                'trx_id' => $trxId,
            ]);

            foreach ($request->input('products') as $product) {
                SaleDetail::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                    'amount' => $product['amount'],
                    'price_rate' => $product['price_rate'],
                ]);


                $productModel = Product::find($product['product_id']);
                $productModel->quantity -= $product['quantity'];
                $productModel->save();
            }


            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $fileName = time() . '_' . $file->getClientOriginalName();

                $file->storeAs('public/sale_attachments', $fileName);

                $sale->attachment = $fileName;
                $sale->save();
            }

            $creditTransaction = Transaction::create([
                'account_name' => 'বিক্রয়',
                'amount' => $sale->total,
                'type' => 'credit',
                'reference_id' => $sale->id,
                'transaction_type' => 'sale',
                'customer_id' => $sale->customer_id,
                'date' => $sale->date,
                'trx_id' => $sale->trx_id,
            ]);

            $creditTransaction->balance = $sale->customer->remaining_due;
            $creditTransaction->save();

            if ($sale->paid > 0) {
                $account = Account::find($request->input('account_id'));
                $debitTransaction = Transaction::create([
                    'account_id' => $request->input('account_id'),
                    'account_name' => $account->name,
                    'amount' => $sale->paid,
                    'type' => 'debit',
                    'reference_id' => $sale->id,
                    'transaction_type' => 'customer_payment',
                    'customer_id' => $sale->customer_id,
                    'date' => $sale->date,
                    'cheque_no' => $request->input('cheque_no'),
                    'cheque_details' => $request->input('cheque_details'),
                    'note' => $request->input('note'),
                    'trx_id' => $sale->trx_id,
                ]);

                $debitTransaction->balance = $sale->customer->remaining_due;
                $debitTransaction->save();

            }

            $paid = $request->input('paid');
            $remain = $request->input('total') - $paid;

            if ($remain > 0) {
                Transaction::create([
                    'account_name' => $sale->customer->name,
                    'amount' => $remain,
                    'type' => 'debit',
                    'reference_id' => $sale->id,
                    'transaction_type' => 'customer_due',
                    'customer_id' => $sale->customer_id,
                    'date' => $sale->date,
                    'trx_id' => $sale->trx_id,
                ]);
            }


            DB::commit();

        } catch (\Exception $e) {

            DB::rollBack();

            \Log::error($e);

            return redirect()->back()->with('error', 'Sale entry failed. Please try again.');
        }

        return redirect()->route('sales.create')->with('success', 'Sale entry successful!');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
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
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sale = Sale::find($id);
        $customers = Customer::all();
        $products = Product::all();
        $payment = Transaction::where('trx_id', $sale->trx_id)
            ->where('transaction_type', 'customer_payment')
            ->first();
        return view('sale.edit', compact('sale', 'customers', 'products', 'payment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Sale $sale
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
            $existingDebitTransaction = Transaction::where('trx_id', $sale->trx_id)
                ->where('transaction_type', 'sale')
                ->where('type', 'credit')
                ->first();

            if ($existingDebitTransaction) {
                // If the new request has a total amount, update the existing transaction
                if ($request->input('total')) {
                    $existingDebitTransaction->update([
                        'amount' => $sale->total,
                        'date' => $sale->date,
                        'customer_id' => $sale->customer_id,
                    ]);
                    $existingDebitTransaction->balance = $sale->customer->remaining_due;
                    $existingDebitTransaction->save();
                }
            } else {
                if ($request->input('total')) {
                    $creditTransaction = Transaction::create([
                        'account_name' => 'বিক্রয়',
                        'amount' => $sale->total,
                        'type' => 'credit',
                        'reference_id' => $sale->id,
                        'transaction_type' => 'sale',
                        'customer_id' => $sale->customer_id,
                        'date' => $sale->date,
                        'trx_id' => $sale->trx_id,
                        'user_id' => Auth::id()
                    ]);

                    $creditTransaction->balance = $sale->customer->remaining_due;
                    $creditTransaction->save();
                }
            }

            // If there was a paid transaction before, update or delete it
            $debitTransaction = Transaction::where('trx_id', $sale->trx_id)
                ->where('transaction_type', 'customer_payment')
                ->where('type', 'debit')
                ->first();

            if ($debitTransaction) {
                // If the new request has a paid amount, update the existing transaction
                if ($sale->paid > 0) {
                    $account = Account::find($request->input('account_id'));
                    $debitTransaction->update([
                        'amount' => $sale->paid,
                        'date' => $sale->date,
                        'account_id' => $request->input('account_id'),
                        'account_name' => $account->name,
                        'customer_id' => $request->input('customer_id'),
                        'cheque_no' => $request->input('cheque_no'),
                        'cheque_details' => $request->input('cheque_details'),
                        'user_id' => Auth::id()
                    ]);
                    $debitTransaction->balance = $sale->customer->remaining_due;
                    $debitTransaction->save();
                } else {
                    // If the new request doesn't have a paid amount, delete the existing transaction
                    $debitTransaction->delete();
                }
            } else {
                if ($sale->paid > 0) {
                    $account = Account::find($request->input('account_id'));
                    // If there wasn't an existing transaction, and the new request has a paid amount, create a new transaction
                    $debitTransaction = Transaction::create([
                        'account_id' => $request->input('account_id'),
                        'account_name' => $account->name,
                        'amount' => $sale->paid,
                        'type' => 'debit',
                        'reference_id' => $sale->id,
                        'transaction_type' => 'customer_payment',
                        'customer_id' => $sale->customer_id,
                        'date' => $sale->date,
                        'cheque_no' => $request->input('cheque_no'),
                        'cheque_details' => $request->input('cheque_details'),
                        'note' => $request->input('note'),
                        'trx_id' => $sale->trx_id,
                    ]);

                    $debitTransaction->balance = $sale->customer->remaining_due;
                    $debitTransaction->save();
                }
            }
            $paid = $request->input('paid');
            $remain = $request->input('total') - $paid;
            $debitTransaction1 = Transaction::where('type', 'credit')
                ->where('transaction_type', 'customer_due')->where('trx_id', $sale->trx_id)->first();
            if ($debitTransaction1) {
                if ($remain > 0) {
                    $debitTransaction1->amount = $remain;
                    $debitTransaction1->date = $sale->date;
                    $debitTransaction1->save();
                } else {
                    $debitTransaction1->delete();
                }
            } else {
                if ($remain > 0) {
                    Transaction::create([
                        'amount' => $remain,
                        'type' => 'debit',
                        'reference_id' => $sale->id,
                        'transaction_type' => 'customer_due',
                        'customer_id' => $sale->customer_id,
                        'account_name' => $sale->customer->name,
                        'date' => $sale->date,
                        'trx_id' => $sale->trx_id,
                    ]);
                }
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

            $saleReturn = SaleReturn::where('sale_id', $sale->id)->first();
            if ($saleReturn) {
                foreach ($saleReturn->saleReturnDetail as $item) {
                    // Adjust product quantity
                    $product = $item->product;
                    $product->quantity -= $item->quantity;
                    $product->save();

                    // Delete sale detail
                    $item->delete();
                }

                if ($saleReturn->attachment) {
                    Storage::delete('public/sale_return_attachments/' . $saleReturn->attachment);
                }

                Transaction::where('trx_id', $saleReturn->trx_id)->delete();
                $saleReturn->delete();
            }
            // If there was a debit transaction, delete it
            Transaction::where('trx_id', $sale->trx_id)->delete();

            // Delete the attachment
            if ($sale->attachment) {
                Storage::delete('public/sale_attachments/' . $sale->attachment);
            }

            // Delete the sale record
            $sale->delete();

            // Commit the transaction
            DB::commit();

            return response()->json(['message' => 'Sale deletion successful!'], 200);
        } catch (\Exception $e) {
            // Rollback the transaction in case of an exception
            DB::rollBack();

            return response()->json(['message' => 'Sale deletion failed. Please try again.'], 500);
        }

        return response()->json(['message' => 'Sale deletion successful!'], 200);
    }
}
