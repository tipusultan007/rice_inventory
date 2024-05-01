<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Supplier;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Class TransactionController
 * @package App\Http\Controllers
 */
class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->has('date1') && $request->has('date2')) {
            $transactions = Transaction::with('account', 'customer', 'supplier')
                ->whereBetween('date',[$request->input('date1'), $request->input('date2')])
                ->orderByDesc('date')
                ->orderBy('trx_id')
                ->paginate(50);
        }else{
            $transactions = Transaction::with('account', 'customer', 'supplier')
                ->orderByDesc('date')
                ->orderBy('trx_id')
                ->paginate(50);
        }

        return view('transaction.index', compact('transactions'))
            ->with('i', (request()->input('page', 1) - 1) * $transactions->perPage());
    }

    public function customerTransactions()
    {
        $transactions = Transaction::whereNotNull('customer_id')
            ->whereNotNull('account_id')
            ->where('transaction_type', 'customer_payment')
            ->where('type', 'debit')
            ->orderByDesc('id')->paginate(30);

        $customers = Customer::all();
        $accounts = Account::all();

        $lastTrx = Transaction::where('user_id', Auth::id())->latest()->first();

        return view('transaction.customer', compact('transactions', 'customers', 'accounts', 'lastTrx'));
    }

    public function supplierTransactions()
    {
        $transactions = Transaction::whereNotNull('supplier_id')
            ->whereNotNull('account_id')
            ->where('transaction_type', 'supplier_payment')
            ->where('type', 'credit')
            ->orderByDesc('id')->paginate(30);

        $suppliers = Supplier::all();
        $accounts = Account::all();
        $lastTrx = Transaction::where('user_id', Auth::id())->latest()->first();

        return view('transaction.supplier', compact('transactions', 'suppliers', 'accounts', 'lastTrx'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $transaction = new Transaction();
        return view('transaction.create', compact('transaction'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        request()->validate(Transaction::$rules);

        $transaction = Transaction::create($request->all());

        return redirect()->back()
            ->with('success', 'Transaction created successfully.');
    }

    public function supplierPayment(Request $request)
    {
        DB::beginTransaction();

        try {
            request()->validate(Transaction::$rules);

            $trxId = Str::uuid();
            $supplier = Supplier::find($request->supplier_id);

            if ($request->input('discount') > 0) {
                $income = Income::create([
                    'income_category_id' => 4,
                    'description' => $supplier->name . ' - প্রাপ্ত নগদ বাট্টা',
                    'date' => $request->date,
                    'amount' => $request->input('discount'),
                    'user_id' => Auth::id(),
                    'trx_id' => $trxId
                ]);
                $creditTransaction1 = Transaction::create([
                    'account_name' => 'প্রাপ্ত নগদ বাট্টা',
                    'supplier_id' => $request->supplier_id,
                    'amount' => $request->input('discount'),
                    'type' => 'credit',
                    'transaction_type' => 'discount',
                    'date' => $request->date,
                    'user_id' => Auth::id(),
                    'trx_id' => $trxId,
                    'note' => $request->input('note'),
                ]);
            }

            if ($request->input('amount') > 0) {
                $account = Account::find($request->input('account_id'));
                $creditTransaction = Transaction::create([
                    'account_id' => $request->account_id,
                    'account_name' => $account->name,
                    'supplier_id' => $request->supplier_id,
                    'amount' => $request->input('amount'),
                    'type' => 'credit',
                    'transaction_type' => 'supplier_payment',
                    'date' => $request->date,
                    'user_id' => Auth::id(),
                    'trx_id' => $trxId,
                    'cheque_no' => $request->input('cheque_no'),
                    'cheque_details' => $request->input('cheque_details'),
                    'note' => $request->input('note'),
                ]);

                $creditTransaction->balance = $supplier->remaining_due;
                $creditTransaction->save();
            }

            $debitTransaction = Transaction::create([
                'account_name' => $supplier->name,
                'supplier_id' => $request->supplier_id,
                'amount' => $request->input('amount') + $request->input('discount'),
                'type' => 'debit',
                'transaction_type' => 'supplier_payment',
                'date' => $request->date,
                'user_id' => Auth::id(),
                'trx_id' => $trxId,
            ]);

            DB::commit();

            return redirect()->back()
                ->with('success', 'Transaction created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred. Transaction rolled back.');
        }
    }

    public function customerPayment(Request $request)
    {
        DB::beginTransaction();

        try {
            request()->validate(Transaction::$rules);
            $customer = Customer::find($request->customer_id);
            $trxId = Str::uuid();
            $account = Account::find($request->input('account_id'));

            // Create credit transaction for customer payment
            $creditTransaction = Transaction::create([
                'account_name' => $customer->name,
                'customer_id' => $request->customer_id,
                'amount' => $request->input('amount') + $request->input('discount'),
                'type' => 'credit',
                'transaction_type' => 'customer_payment',
                'date' => $request->date,
                'user_id' => Auth::id(),
                'trx_id' => $trxId,
            ]);

            // Create discount expense and corresponding debit transaction if discount is applied
            if ($request->input('discount')) {
                $expense = Expense::create([
                    'expense_category_id' => 21,
                    'amount' => $request->input('discount'),
                    'date' => $request->input('date'),
                    'description' => $customer->name . ' - নগদ প্রদত্ত বাট্টা',
                    'user_id' => Auth::id(),
                    'trx_id' => $trxId
                ]);

                $debitTransaction = Transaction::create([
                    'account_name' => 'নগদ প্রদত্ত বাট্টা',
                    'customer_id' => $request->customer_id,
                    'amount' => $request->input('discount'),
                    'type' => 'debit',
                    'transaction_type' => 'discount',
                    'reference_id' => $expense->id,
                    'date' => $request->date,
                    'user_id' => Auth::id(),
                    'trx_id' => $trxId,
                ]);
            }

            // Create debit transaction for customer payment
            if ($request->input('amount')) {
                $debitTransaction1 = Transaction::create([
                    'account_id' => $request->account_id,
                    'account_name' => $account->name,
                    'customer_id' => $request->customer_id,
                    'amount' => $request->input('amount'),
                    'type' => 'debit',
                    'transaction_type' => 'customer_payment',
                    'date' => $request->date,
                    'user_id' => Auth::id(),
                    'trx_id' => $trxId,
                    'cheque_no' => $request->input('cheque_no'),
                    'cheque_details' => $request->input('cheque_details'),
                    'note' => $request->input('note'),
                ]);

                $debitTransaction1->balance = $customer->remaining_due;
                $debitTransaction1->save();
            }

            DB::commit();

            return redirect()->back()
                ->with('success', 'Transaction created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred. Transaction rolled back.');
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
        $transaction = Transaction::find($id);

        return view('transaction.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $transaction = Transaction::find($id);
        $accounts = Account::all();

        return view('transaction.edit', compact('transaction', 'accounts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Transaction $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        //request()->validate(Transaction::$rules);

        $transaction->update($request->all());

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $transaction = Transaction::find($id);
        Expense::where('trx_id', $transaction->trx_id)->delete();
        Income::where('trx_id', $transaction->trx_id)->delete();

        Transaction::where('trx_id', $transaction->trx_id)->delete();

        return redirect()->back()
            ->with('success', 'Transaction deleted successfully');
    }
}
