<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnDetail;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Class PurchaseReturnController
 * @package App\Http\Controllers
 */
class PurchaseReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $purchaseReturns = PurchaseReturn::orderByDesc('created_at')->paginate(10);

        return view('purchase-return.index', compact('purchaseReturns'))
            ->with('i', (request()->input('page', 1) - 1) * $purchaseReturns->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $purchaseReturn = new PurchaseReturn();
        $purchases = Purchase::pluck('invoice_no','id');
        if ($request->filled('purchase_id')) {
            $purchase = Purchase::find($request->input('purchase_id'));
            return view('purchase-return.create', compact('purchaseReturn','purchases','purchase'));
        }
        return view('purchase-return.create', compact('purchaseReturn','purchases'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(PurchaseReturn::$rules);

        $request->validate([
            'paid' => 'nullable',
            'account_id' => ['required_with:paid'],
        ],[
            'account_id' => 'অ্যাকাউন্ট সিলেক্ট করুন'
        ]);


        try {
            DB::beginTransaction();

            $data = $request->all();
            $data['trx_id'] = Str::uuid();

            $purchaseReturn = PurchaseReturn::create($data);

            // Handle products outside the loop
            $this->handlePurchaseReturnDetails($request->input('products'), $purchaseReturn);

            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $fileName = time() . '_' . $file->getClientOriginalName();

                $file->storeAs('public/purchase_return_attachments', $fileName);

                $purchaseReturn->attachment = $fileName;
                $purchaseReturn->save();
            }

            Transaction::create([
                'account_name' => 'ক্রয় ফেরত',
                'amount' => $purchaseReturn->total,
                'type' => 'credit',
                'reference_id' => $purchaseReturn->id,
                'transaction_type' => 'purchase_return',
                'supplier_id' => $purchaseReturn->supplier_id,
                'date' => $purchaseReturn->date,
                'trx_id' => $purchaseReturn->trx_id,
            ]);

            $paid = $request->input('paid');
            $remain = $request->input('total') - $paid;

            if ($paid > 0) {
                $account = Account::find($request->input('account_id'));
               $debitTransaction = Transaction::create([
                    'account_id' => $request->input('account_id'),
                    'account_name' => $account->name,
                    'amount' => $paid,
                    'type' => 'debit',
                    'reference_id' => $purchaseReturn->id,
                    'transaction_type' => 'payment_from_supplier',
                    'supplier_id' => $purchaseReturn->supplier_id,
                    'date' => $purchaseReturn->date,
                    'trx_id' => $purchaseReturn->trx_id,
                ]);
               $debitTransaction->balance = $purchaseReturn->supplier->remaining_due;
               $debitTransaction->save();
            }



            if ($remain > 0){

                $debitTransaction1 = Transaction::create([
                    'account_name' => $purchaseReturn->supplier->name,
                    'amount' => $remain,
                    'type' => 'debit',
                    'reference_id' => $purchaseReturn->id,
                    'transaction_type' => 'due_from_supplier',
                    'supplier_id' => $purchaseReturn->supplier_id,
                    'date' => $purchaseReturn->date,
                    'trx_id' => $purchaseReturn->trx_id,
                ]);
                $debitTransaction1->balance = $purchaseReturn->supplier->remaining_due;
                $debitTransaction1->save();
            }

            DB::commit();
        } catch (\PDOException | \Exception $e) {
            DB::rollBack();

            if ($e instanceof \Illuminate\Validation\ValidationException) {
                return redirect()->back()->withErrors($e->validator)->withInput();
            }

            return redirect()->back()->with('error', 'Purchase return entry failed. Please try again.');
        }

        return redirect()->route('purchase_returns.index')
            ->with('success', 'Purchase Return created successfully.');
    }
    function handlePurchaseReturnDetails($products, $purchase)
    {
        foreach ($products as $product) {
            PurchaseReturnDetail::create([
                'purchase_return_id' => $purchase->id,
                'product_id' => $product['product_id'],
                'quantity' => $product['quantity'],
                'amount' => $product['amount'],
                'price_rate' => $product['price_rate'],
            ]);

            $productModel = Product::find($product['product_id']);
            $productModel->quantity -= $product['quantity'];
            $productModel->save();
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
        $purchaseReturn = PurchaseReturn::find($id);

        return view('purchase-return.show', compact('purchaseReturn'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $purchaseReturn = PurchaseReturn::find($id);

        return view('purchase-return.edit', compact('purchaseReturn'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  PurchaseReturn $purchaseReturn
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PurchaseReturn $purchaseReturn)
    {
        request()->validate(PurchaseReturn::$rules);

        $purchaseReturn->update($request->all());

        return redirect()->route('purchase_returns.index')
            ->with('success', 'PurchaseReturn updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $purchaseReturn = PurchaseReturn::find($id);
        Transaction::where('trx_id', $purchaseReturn->trx_id)->delete();

        //Transaction::where('transaction_type','purchase_return')->where('reference_id', $purchaseReturn->id)->delete();

        foreach ($purchaseReturn->purchaseReturnDetail as $detail){
            $product = $detail->product;
            $product->quantity += $detail->quantity;
            $product->save();

            $detail->delete();
        }

        $purchaseReturn->delete();

        return redirect()->route('purchase_returns.index')
            ->with('success', 'PurchaseReturn deleted successfully');
    }
}
