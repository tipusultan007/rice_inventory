<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Capital;
use App\Models\CapitalWithdraw;
use App\Models\Expense;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Class CapitalWithdrawController
 * @package App\Http\Controllers
 */
class CapitalWithdrawController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $capitalWithdraws = CapitalWithdraw::orderBy('id','desc')->paginate(10);

        return view('capital-withdraw.index', compact('capitalWithdraws'))
            ->with('i', (request()->input('page', 1) - 1) * $capitalWithdraws->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $capitalWithdraw = new CapitalWithdraw();
        return view('capital-withdraw.create', compact('capitalWithdraw'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'amount' => ['required_without:interest'],
            'interest' => ['required_without:amount'],
            'account_id' => 'required',
            'capital_id' => 'required',
            'date' => 'required|date',
        ]);

        DB::beginTransaction();

        try {
            $data = $request->all();
            $data['trx_id'] = Str::uuid();
            $data['user_id'] = Auth::id();
            $capitalWithdraw = CapitalWithdraw::create($data);

            if ($request->input('amount') > 0) {
                $account = Account::find($request->input('account_id'));
                Transaction::create([
                    'account_id' => $request->input('account_id'),
                    'account_name' => $account->name,
                    'amount' => $request->input('amount'),
                    'type' => 'credit',
                    'reference_id' => $capitalWithdraw->id,
                    'date' => $request->input('date'),
                    'transaction_type' => 'capital_withdraw',
                    'user_id' => Auth::id(),
                    'trx_id' => $capitalWithdraw->trx_id
                ]);

                //এক্সপেন্সে
                Transaction::create([
                    'amount' => $request->input('amount'),
                    'account_name' => $capitalWithdraw->capital->name,
                    'type' => 'debit',
                    'date' => $request->input('date'),
                    'transaction_type' => 'capital_withdraw',
                    'reference_id' => $capitalWithdraw->id,
                    'user_id' => Auth::id(),
                    'trx_id' => $capitalWithdraw->trx_id
                ]);

                // Update loan balance
                $loan = Capital::find($request->input('capital_id'));

                $capitalWithdraw->balance = $loan->balance;
                $capitalWithdraw->save();

            }

            if ($request->input('interest') > 0) {

                $expense = Expense::create([
                    'expense_category_id' => 19,
                    'date' => $capitalWithdraw->date,
                    'description' => $capitalWithdraw->capital->name.' - মুনাফা',
                    'amount' => $capitalWithdraw->interest,
                    'user_id' => Auth::id(),
                    'trx_id' => $capitalWithdraw->trx_id
                ]);

                $account = Account::find($request->input('account_id'));
                // Create a transaction for the balance transfer from the source account
                Transaction::create([
                    'account_id' => $request->input('account_id'),
                    'account_name' => $account->name,
                    'amount' => $expense->amount,
                    'type' => 'credit',
                    'reference_id' => $expense->id,
                    'date' => $expense->date,
                    'transaction_type' => 'expense',
                    'user_id' => Auth::id(),
                    'trx_id' => $expense->trx_id,
                ]);

                // Create a transaction for the balance transfer to the destination account
                Transaction::create([
                    'account_name' => $expense->description,
                    'amount' => $expense->amount,
                    'type' => 'debit',
                    'reference_id' => $expense->id,
                    'date' => $expense->date,
                    'transaction_type' => 'expense',
                    'user_id' => Auth::id(),
                    'trx_id' => $expense->trx_id,
                ]);
                $loan = Capital::find($request->input('capital_id'));

                $capitalWithdraw->balance = $loan->balance;
                $capitalWithdraw->total_interest = $loan->capital_profit;
                $capitalWithdraw->save();
            }

            // Commit the transaction
            DB::commit();

            return redirect()->back()->with('success', 'Loan payment successfully added!');
        } catch (\Exception $e) {
            // Something went wrong, rollback the transaction
            DB::rollback();
            \Log::error($e);
            return redirect()->back()->with('error', 'An error occurred while processing the loan payment. Please try again.');
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
        $capitalWithdraw = CapitalWithdraw::find($id);

        return view('capital-withdraw.show', compact('capitalWithdraw'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $capitalWithdraw = CapitalWithdraw::find($id);

        $transaction = Transaction::where('trx_id',$capitalWithdraw->trx_id)->whereNotNull('account_id')->first();

        return view('capital-withdraw.edit', compact('capitalWithdraw','transaction'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  CapitalWithdraw $capitalWithdraw
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CapitalWithdraw $capitalWithdraw)
    {
        $request->validate([
            'amount' => ['required_without:interest'],
            'interest' => ['required_without:amount'],
            'account_id' => 'required',
            'date' => 'required|date',
        ]);


        DB::beginTransaction();

        try {
            //$capitalWithdraw = CapitalWithdraw::findOrFail($id);
            $data = $request->all();
            $data['user_id'] = Auth::id();

            // Update capital withdraw
            $capitalWithdraw->update($data);
            $capital = Capital::find($capitalWithdraw->capital_id);
            $capitalWithdraw->balance = $capital->balance;
            $capitalWithdraw->total_interest = $capital->capital_profit;
            $capitalWithdraw->save();

            $account = Account::find($request->input('account_id'));

            $creditTransaction = Transaction::where('trx_id', $capitalWithdraw->trx_id)
                ->where('transaction_type', 'capital_withdraw')
                ->where('type', 'credit')
                ->first();

            $debitTransaction = Transaction::where('trx_id', $capitalWithdraw->trx_id)
                ->where('transaction_type', 'capital_withdraw')
                ->where('type', 'debit')
                ->first();

            if ($creditTransaction){
                if ($request->input('amount') > 0){
                    $creditTransaction->amount = $request->input('amount');
                    $creditTransaction->date = $request->input('date');
                    $creditTransaction->account_id = $request->input('account_id');
                    $creditTransaction->account_name = $account->name;
                    $creditTransaction->save();
                }else{
                    $creditTransaction->delete();
                }
            }else{
                if ($request->input('amount')>0) {
                    Transaction::create([
                        'account_id' => $request->input('account_id'),
                        'account_name' => $account->name,
                        'amount' => $request->input('amount'),
                        'type' => 'credit',
                        'reference_id' => $capitalWithdraw->id,
                        'date' => $request->input('date'),
                        'transaction_type' => 'capital_withdraw',
                        'user_id' => Auth::id(),
                        'trx_id' => $capitalWithdraw->trx_id
                    ]);
                }
            }

            if ($debitTransaction){
                if ($request->input('amount') > 0){
                    $debitTransaction->amount = $request->input('amount');
                    $debitTransaction->date = $request->input('date');
                    $debitTransaction->save();
                }else{
                    $debitTransaction->delete();
                }
            }else{
                if ($request->input('amount') > 0){
                    Transaction::create([
                        'amount' => $request->input('amount'),
                        'account_name' => $capitalWithdraw->capital->name,
                        'type' => 'debit',
                        'date' => $request->input('date'),
                        'transaction_type' => 'capital_withdraw',
                        'reference_id' => $capitalWithdraw->id,
                        'user_id' => Auth::id(),
                        'trx_id' => $capitalWithdraw->trx_id
                    ]);
                }
            }

            $expense = Expense::where('trx_id', $capitalWithdraw->trx_id)->first();
            if ($expense){
                if ($request->input('interest') > 0) {

                    $expense->amount = $capitalWithdraw->interest;
                    $expense->date = $capitalWithdraw->date;
                    $expense->save();

                    // Update transaction for interest
                    $transaction = Transaction::where('reference_id', $expense->id)
                        ->where('transaction_type', 'expense')
                        ->where('type', 'credit')
                        ->firstOrFail();

                    $transaction->amount = $capitalWithdraw->interest;
                    $transaction->date = $capitalWithdraw->date;
                    $transaction->save();

                    // Update transaction for interest expense
                    $expenseTransaction = Transaction::where('reference_id', $expense->id)
                        ->where('transaction_type', 'expense')
                        ->where('type', 'debit')
                        ->firstOrFail();

                    $expenseTransaction->amount = $capitalWithdraw->interest;
                    $expenseTransaction->date = $capitalWithdraw->date;
                    $expenseTransaction->save();

                }else{
                    Transaction::where('reference_id', $expense->id)->delete();
                    $expense->delete();
                }
            }else{
                if ($request->input('interest') > 0) {

                    $expense = Expense::create([
                        'expense_category_id' => 19,
                        'date' => $capitalWithdraw->date,
                        'description' => $capitalWithdraw->capital->name.' - মুনাফা',
                        'amount' => $capitalWithdraw->interest,
                        'user_id' => Auth::id(),
                        'trx_id' => $capitalWithdraw->trx_id
                    ]);

                    $account = Account::find($request->input('account_id'));
                    // Create a transaction for the balance transfer from the source account
                    Transaction::create([
                        'account_id' => $request->input('account_id'),
                        'account_name' => $account->name,
                        'amount' => $expense->amount,
                        'type' => 'credit',
                        'reference_id' => $expense->id,
                        'date' => $expense->date,
                        'transaction_type' => 'expense',
                        'user_id' => Auth::id(),
                        'trx_id' => $expense->trx_id,
                    ]);

                    // Create a transaction for the balance transfer to the destination account
                    Transaction::create([
                        'account_name' => $expense->description,
                        'amount' => $expense->amount,
                        'type' => 'debit',
                        'reference_id' => $expense->id,
                        'date' => $expense->date,
                        'transaction_type' => 'expense',
                        'user_id' => Auth::id(),
                        'trx_id' => $expense->trx_id,
                    ]);
                }
            }

            $capital = Capital::find($capitalWithdraw->capital_id);
            $capitalWithdraw->balance = $capital->balance;
            $capitalWithdraw->total_interest = $capital->capital_profit;
            $capitalWithdraw->save();

            // Commit the transaction
            DB::commit();

            return redirect()->back()->with('success', 'মূলধন উত্তোলন সফল হয়েছে!');
        } catch (\Exception $e) {
            // Something went wrong, rollback the transaction
            DB::rollback();
            \Log::error($e);
            return redirect()->back()->with('error', 'মূলধন উত্তোলন ব্যর্থ হয়েছে!');
        }
    }


    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $capitalWithdraw = CapitalWithdraw::find($id);
        Expense::where('trx_id', $capitalWithdraw->trx_id)->delete();
        Transaction::where('trx_id', $capitalWithdraw->trx_id)->delete();

        $capitalWithdraw->delete();

        return redirect()->route('capital_withdraws.index')
            ->with('success', 'CapitalWithdraw deleted successfully');
    }
}
