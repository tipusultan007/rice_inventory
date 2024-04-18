<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\SaleReturn;
use App\Models\SaleReturnDetail;
use App\Models\Transaction;
use App\Rules\PaidWithAccountIdRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Class SaleReturnController
 * @package App\Http\Controllers
 */
class SaleReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $saleReturns = SaleReturn::paginate(10);

        return view('sale-return.index', compact('saleReturns'))
            ->with('i', (request()->input('page', 1) - 1) * $saleReturns->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $saleReturn = new SaleReturn();
        $sales = Sale::pluck('invoice_no', 'id');
        if ($request->filled('sale_id')) {
            $sale = Sale::find($request->input('sale_id'));
            return view('sale-return.create', compact('saleReturn', 'sales', 'sale'));
        }
        return view('sale-return.create', compact('saleReturn', 'sales'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(SaleReturn::$rules);

        $request->validate([
            'paid' => 'nullable',
            'account_id' => ['required_with:paid'],
        ], [
            'account_id' => 'অ্যাকাউন্ট সিলেক্ট করুন'
        ]);


        try {
            DB::beginTransaction();

            $data = $request->all();
            $data['trx_id'] = Str::uuid();
            $data['user_id'] = Auth::id();

            $sale = SaleReturn::create($data);

            // Handle products outside the loop
            $this->handleSaleReturnDetails($request->input('products'), $sale);

            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $fileName = time() . '_' . $file->getClientOriginalName();

                $file->storeAs('public/sale_return_attachments', $fileName);

                $sale->attachment = $fileName;
                $sale->save();
            }

            $debitTransaction = Transaction::create([
                'account_name' => 'বিক্রয় ফেরত',
                'amount' => $request->input('total'),
                'type' => 'debit',
                'reference_id' => $sale->id,
                'transaction_type' => 'sale_return',
                'customer_id' => $sale->customer_id,
                'date' => $sale->date,
                'user_id' => Auth::id(),
                'note' => $sale->note,
                'trx_id' => $sale->trx_id
            ]);

            $paid = $request->input('paid');
            $remain = $request->input('total') - $paid;

            if ($paid > 0) {
                $account = Account::find($request->input('account_id'));
                $creditTransaction = Transaction::create([
                    'account_id' => $request->input('account_id'),
                    'account_name' => $account->name,
                    'amount' => $paid,
                    'type' => 'credit',
                    'reference_id' => $sale->id,
                    'transaction_type' => 'payment_to_customer',
                    'customer_id' => $sale->customer_id,
                    'date' => $sale->date,
                    'trx_id' => $sale->trx_id,
                ]);
            }

            if ($remain > 0) {
                $credit = Transaction::create([
                    'account_name' => $sale->customer->name,
                    'amount' => $remain,
                    'type' => 'credit',
                    'reference_id' => $sale->id,
                    'transaction_type' => 'due_to_customer',
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

        return redirect()->route('sale_returns.index')
            ->with('success', 'SaleReturn created successfully.');
    }

    function handleSaleReturnDetails($products, $sale)
    {
        foreach ($products as $product) {
            SaleReturnDetail::create([
                'sale_return_id' => $sale->id,
                'product_id' => $product['product_id'],
                'quantity' => $product['quantity'],
                'amount' => $product['amount'],
                'price_rate' => $product['price_rate'],
            ]);

            $productModel = Product::find($product['product_id']);
            $productModel->quantity += $product['quantity'];
            $productModel->save();
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
        $saleReturn = SaleReturn::find($id);

        return view('sale-return.show', compact('saleReturn'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $saleReturn = SaleReturn::find($id);

        return view('sale-return.edit', compact('saleReturn'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param SaleReturn $saleReturn
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SaleReturn $saleReturn)
    {
        request()->validate(SaleReturn::$rules);

        $request->validate([
            'paid' => 'nullable',
            'account_id' => ['required_with:paid'],
        ], [
            'account_id' => 'অ্যাকাউন্ট সিলেক্ট করুন'
        ]);

        try {
            DB::beginTransaction();

            $saleReturn->update($request->all());

            // Delete existing sale return details
            $saleReturn->saleReturnDetails()->delete();

            // Handle products outside the loop
            $this->handleSaleReturnDetails($request->input('products'), $saleReturn);

            // Update attachment if new one provided
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $fileName = time() . '_' . $file->getClientOriginalName();

                $file->storeAs('public/sale_return_attachments', $fileName);

                $saleReturn->attachment = $fileName;
                $saleReturn->save();
            }

            // Create or update transactions
            $total = $request->input('total');
            $paid = $request->input('paid');
            $remain = $total - $paid;

            // Update existing credit transaction
            $creditTransaction = Transaction::where('reference_id', $saleReturn->id)
                ->where('transaction_type', 'sale_return')
                ->where('type', 'credit')
                ->first();

            if ($creditTransaction) {
                $creditTransaction->update([
                    'amount' => $total,
                    'customer_id' => $saleReturn->customer_id,
                    'date' => $saleReturn->date,
                    'note' => $saleReturn->note
                ]);
            } else {
                Transaction::create([
                    'amount' => $total,
                    'type' => 'credit',
                    'reference_id' => $saleReturn->id,
                    'transaction_type' => 'sale_return',
                    'customer_id' => $saleReturn->customer_id,
                    'date' => $saleReturn->date,
                    'user_id' => Auth::id(),
                    'note' => $saleReturn->note
                ]);
            }

            // Update or create debit transactions
            if ($paid > 0) {
                $debitTransaction = Transaction::where('reference_id', $saleReturn->id)
                    ->where('transaction_type', 'sale_return')
                    ->where('type', 'debit')
                    ->first();

                if ($debitTransaction) {
                    $debitTransaction->update([
                        'account_id' => $request->input('account_id'),
                        'amount' => $paid,
                        'customer_id' => $saleReturn->customer_id,
                        'date' => $saleReturn->date,
                    ]);
                } else {
                    Transaction::create([
                        'account_id' => $request->input('account_id'),
                        'amount' => $paid,
                        'type' => 'debit',
                        'reference_id' => $saleReturn->id,
                        'transaction_type' => 'sale_return',
                        'customer_id' => $saleReturn->customer_id,
                        'date' => $saleReturn->date,
                    ]);
                }
            }

            // Update or create remaining debit transactions
            if ($remain > 0) {
                $remainingTransaction = Transaction::where('reference_id', $saleReturn->id)
                    ->where('transaction_type', 'sale_return')
                    ->where('type', 'debit')
                    ->skip($paid > 0 ? 1 : 0) // Skip the paid transaction if present
                    ->first();

                if ($remainingTransaction) {
                    $remainingTransaction->update([
                        'amount' => $remain,
                        'customer_id' => $saleReturn->customer_id,
                        'date' => $saleReturn->date,
                    ]);
                } else {
                    Transaction::create([
                        'amount' => $remain,
                        'type' => 'debit',
                        'reference_id' => $saleReturn->id,
                        'transaction_type' => 'sale_return',
                        'customer_id' => $saleReturn->customer_id,
                        'date' => $saleReturn->date,
                    ]);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Sale return update failed. Please try again.');
        }

        return redirect()->route('sale_returns.index')->with('success', 'SaleReturn updated successfully.');
    }


    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $saleReturn = SaleReturn::find($id);

            // Delete related transactions
            Transaction::where('transaction_type', 'sale_return')->where('reference_id', $saleReturn->id)->delete();

            // Adjust product quantities and delete sale return details
            foreach ($saleReturn->saleReturnDetail as $detail) {
                $product = $detail->product;
                $product->quantity -= $detail->quantity;
                $product->save();

                $detail->delete();
            }

            // Delete the sale return
            $saleReturn->delete();

            // Commit the transaction
            DB::commit();

            return redirect()->route('sale_returns.index')->with('success', 'SaleReturn deleted successfully');
        } catch (\Exception $e) {
            // Something went wrong, rollback the transaction
            DB::rollBack();

            return redirect()->back()->with('error', 'An error occurred while deleting the sale return. Please try again.');
        }
    }
}
