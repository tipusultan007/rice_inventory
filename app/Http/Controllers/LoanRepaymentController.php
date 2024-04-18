<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Expense;
use App\Models\Loan;
use App\Models\LoanRepayment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Class LoanRepaymentController
 * @package App\Http\Controllers
 */
class LoanRepaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $loanRepayments = LoanRepayment::with('loan')
            ->OrderBy('date','desc')
            ->paginate(10);

        return view('loan-repayment.index', compact('loanRepayments'))
            ->with('i', (request()->input('page', 1) - 1) * $loanRepayments->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $loanRepayment = new LoanRepayment();
        return view('loan-repayment.create', compact('loanRepayment'));
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
            'loan_id' => 'required',
            'date' => 'required|date',
        ]);

        DB::beginTransaction();

        try {
            $data = $request->all();
            $data['trx_id'] = Str::uuid();
            $data['user_id'] = Auth::id();
            $loanRepayment = LoanRepayment::create($data);

            if ($request->input('amount') > 0) {
                $account = Account::find($request->input('account_id'));
                Transaction::create([
                    'account_id' => $request->input('account_id'),
                    'account_name' => $account->name,
                    'amount' => $request->input('amount'),
                    'type' => 'credit',
                    'reference_id' => $loanRepayment->id,
                    'date' => $request->input('date'),
                    'transaction_type' => 'loan_repayment',
                    'user_id' => Auth::id(),
                    'trx_id' => $loanRepayment->trx_id
                ]);

                //এক্সপেন্সে
                Transaction::create([
                    'amount' => $request->input('amount'),
                    'account_name' => $loanRepayment->loan->name,
                    'type' => 'debit',
                    'date' => $request->input('date'),
                    'transaction_type' => 'loan_repayment',
                    'reference_id' => $loanRepayment->id,
                    'user_id' => Auth::id(),
                    'trx_id' => $loanRepayment->trx_id
                ]);

                // Update loan balance
                $loan = Loan::find($request->input('loan_id'));

                $loanRepayment->balance = $loan->balance;
                $loanRepayment->save();

            }

            if ($request->input('interest') > 0) {

                $expense = Expense::create([
                    'expense_category_id' => 17,
                    'date' => $loanRepayment->date,
                    'description' => $loanRepayment->loan->name.' - সুদ',
                    'amount' => $loanRepayment->interest,
                    'user_id' => Auth::id(),
                    'trx_id' => $loanRepayment->trx_id
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

                $loan = Loan::find($request->input('loan_id'));

                $loanRepayment->balance = $loan->balance;
                $loanRepayment->total_interest = $loan->total_interest;
                $loanRepayment->save();
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
        $loanRepayment = LoanRepayment::find($id);

        return view('loan-repayment.show', compact('loanRepayment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $loanRepayment = LoanRepayment::find($id);

        return view('loan-repayment.edit', compact('loanRepayment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  LoanRepayment $loanRepayment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LoanRepayment $loanRepayment)
    {
        request()->validate(LoanRepayment::$rules);

        //$loanRepayment->update($request->all());

        DB::beginTransaction();

        try {
            //$loanRepayment = CapitalWithdraw::findOrFail($id);
            $data = $request->all();
            $data['user_id'] = Auth::id();

            // Update capital withdraw
            $loanRepayment->update($data);
            $loan = Loan::find($loanRepayment->loan_id);
            $loanRepayment->balance = $loan->balance;
            $loanRepayment->total_interest = $loan->total_interest;
            $loanRepayment->save();

            $account = Account::find($request->input('account_id'));

            $creditTransaction = Transaction::where('trx_id', $loanRepayment->trx_id)
                ->where('transaction_type', 'loan_repayment')
                ->where('type', 'credit')
                ->first();

            $debitTransaction = Transaction::where('trx_id', $loanRepayment->trx_id)
                ->where('transaction_type', 'loan_repayment')
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
                        'reference_id' => $loanRepayment->id,
                        'date' => $request->input('date'),
                        'transaction_type' => 'loan_repayment',
                        'user_id' => Auth::id(),
                        'trx_id' => $loanRepayment->trx_id
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
                        'account_name' => $loanRepayment->loan->name,
                        'type' => 'debit',
                        'date' => $request->input('date'),
                        'transaction_type' => 'loan_repayment',
                        'reference_id' => $loanRepayment->id,
                        'user_id' => Auth::id(),
                        'trx_id' => $loanRepayment->trx_id
                    ]);
                }
            }

            $expense = Expense::where('trx_id', $loanRepayment->trx_id)->first();
            if ($expense){
                if ($request->input('interest') > 0) {

                    $expense->amount = $loanRepayment->interest;
                    $expense->date = $loanRepayment->date;
                    $expense->save();

                    // Update transaction for interest
                    $transaction = Transaction::where('reference_id', $expense->id)
                        ->where('transaction_type', 'expense')
                        ->where('type', 'credit')
                        ->firstOrFail();

                    $transaction->amount = $loanRepayment->interest;
                    $transaction->date = $loanRepayment->date;
                    $transaction->save();

                    // Update transaction for interest expense
                    $expenseTransaction = Transaction::where('reference_id', $expense->id)
                        ->where('transaction_type', 'expense')
                        ->where('type', 'debit')
                        ->firstOrFail();

                    $expenseTransaction->amount = $loanRepayment->interest;
                    $expenseTransaction->date = $loanRepayment->date;
                    $expenseTransaction->save();

                }else{
                    Transaction::where('reference_id', $expense->id)->delete();
                    $expense->delete();
                }
            }else{
                if ($request->input('interest') > 0) {

                    $expense = Expense::create([
                        'expense_category_id' => 17,
                        'date' => $loanRepayment->date,
                        'description' => $loanRepayment->loan->name.' - সুদ',
                        'amount' => $loanRepayment->interest,
                        'user_id' => Auth::id(),
                        'trx_id' => $loanRepayment->trx_id
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

            $loan = Loan::find($loanRepayment->loan_id);
            $loanRepayment->balance = $loan->balance;
            $loanRepayment->total_interest = $loan->total_interest;
            $loanRepayment->save();

            // Commit the transaction
            DB::commit();

            return redirect()->back()->with('success', 'মূলধন উত্তোলন সফল হয়েছে!');
        } catch (\Exception $e) {
            // Something went wrong, rollback the transaction
            DB::rollback();
            \Log::error($e);
            return redirect()->back()->with('error', 'মূলধন উত্তোলন ব্যর্থ হয়েছে!');
        }

        return redirect()->route('loan_repayments.index')
            ->with('success', 'LoanRepayment updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $loanRepayment = LoanRepayment::find($id);

        Expense::where('trx_id', $loanRepayment->trx_id)->delete();
        Transaction::where('trx_id',$loanRepayment->trx_id)->delete();
        $loanRepayment->delete();

        return redirect()->back()->with('success', 'LoanRepayment deleted successfully');
    }
}
