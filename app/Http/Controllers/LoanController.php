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
 * Class LoanController
 * @package App\Http\Controllers
 */
class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $loans = Loan::paginate(10);

        return view('loan.index', compact('loans'))
            ->with('i', (request()->input('page', 1) - 1) * $loans->perPage());
    }

    public function loanTransactions()
    {
        $transactions = LoanRepayment::whereNotNull('loan_id')->orderByDesc('id')->paginate(10);
        return view('loan.transactions',compact('transactions'));
    }

    public function loanRepayment(Request $request)
    {
        $request->validate([
            'amount' => ['required_without:loan_interest'],
            'loan_interest' => ['required_without:amount'],
            'account_id' => 'required',
            'loan_id' => 'required',
            'date' => 'required|date',
        ]);

        // Start a database transaction
        DB::beginTransaction();

        try {
            if ($request->input('amount') > 0) {
                Transaction::create([
                    'account_id' => $request->input('account_id'),
                    'amount' => $request->input('amount'),
                    'type' => 'credit',
                    'loan_id' => $request->input('loan_id'),
                    'reference_id' => $request->input('loan_id'),
                    'date' => $request->input('date'),
                    'transaction_type' => 'loan_repayment',
                    'user_id' => Auth::id()
                ]);

                Transaction::create([
                    'amount' => $request->input('amount'),
                    'type' => 'debit',
                    'loan_id' => $request->input('loan_id'),
                    'date' => $request->input('date'),
                    'transaction_type' => 'loan_repayment',
                    'reference_id' => $request->input('loan_id'),
                    'user_id' => Auth::id()
                ]);

                // Update loan balance
                $loan = Loan::find($request->input('loan_id'));
                $loan->balance -= $request->input('amount');
                $loan->save();
            }

            if ($request->input('loan_interest') > 0) {
                Transaction::create([
                    'account_id' => $request->input('account_id'),
                    'amount' => $request->input('loan_interest'),
                    'type' => 'debit',
                    'loan_id' => $request->input('loan_id'),
                    'date' => $request->input('date'),
                    'transaction_type' => 'loan_interest',
                    'reference_id' => $request->input('loan_id'),
                    'user_id' => Auth::id()
                ]);

                Transaction::create([
                    'amount' => $request->input('loan_interest'),
                    'type' => 'credit',
                    'loan_id' => $request->input('loan_id'),
                    'date' => $request->input('date'),
                    'transaction_type' => 'loan_interest',
                    'reference_id' => $request->input('loan_id'),
                    'user_id' => Auth::id()
                ]);
            }

            // Commit the transaction
            DB::commit();

            return redirect()->back()->with('success', 'Loan payment successfully added!');
        } catch (\Exception $e) {
            // Something went wrong, rollback the transaction
            DB::rollback();

            return redirect()->back()->with('error', 'An error occurred while processing the loan payment. Please try again.');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $loan = new Loan();
        return view('loan.create', compact('loan'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(Loan::$rules);

        DB::beginTransaction();

        try {
            $data = $request->all();
            $data['trx_id'] = Str::uuid();
            $loan = Loan::create($data);

            $account = Account::find($request->input('account_id'));
            Transaction::create([
                'account_id' => $request->input('account_id'),
                'account_name' => $account->name,
                'amount' => $loan->loan_amount,
                'type' => 'debit',
                'reference_id' => $loan->id,
                'date' => $loan->date,
                'transaction_type' => 'loan_taken',
                'user_id' => Auth::id(),
                'trx_id' => $loan->trx_id
            ]);

            Transaction::create([
                'account_name' => $loan->name,
                'amount' => $loan->loan_amount,
                'type' => 'credit',
                'reference_id' => $loan->id,
                'date' => $loan->date,
                'transaction_type' => 'loan_taken',
                'user_id' => Auth::id(),
                'trx_id' => $loan->trx_id
            ]);

            DB::commit();

            return redirect()->route('loans.index')->with('success', 'Loan created successfully.');
        } catch (\Exception $e) {

            DB::rollback();
            \Log::error($e);
            return redirect()->back()->with('error', 'An error occurred while creating the loan. Please try again.');
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
        $loan = Loan::find($id);

        $transactions = LoanRepayment::where('loan_id', $loan->id)->get();
        return view('loan.show', compact('loan','transactions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $loan = Loan::find($id);
        $creditTransaction = Transaction::where('trx_id',$loan->trx_id)
            ->where('transaction_type','loan_taken')
            ->where('type','credit')
            ->first();

        return view('loan.edit', compact('loan','creditTransaction'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Loan $loan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Loan $loan)
    {
        $validatedData = $request->validate(Loan::$rules);
        DB::beginTransaction();

        try {
            $loan->update($validatedData);

            $loan->update(['balance' => $loan->loan_amount]);

            $transactions = Transaction::where('trx_id', $loan->trx_id)
                ->where('transaction_type', 'loan_taken')
                ->whereIn('type', ['credit', 'debit'])
                ->get();

            foreach ($transactions as $transaction) {
                if ($transaction->type === 'credit') {
                    $transaction->update([
                        'amount' => $loan->loan_amount,
                        'account_id' => $request->input('account_id'),
                        'date' => $loan->date
                    ]);
                } elseif ($transaction->type === 'debit') {
                    $account = Account::find($request->input('account_id'));
                    $transaction->update([
                        'account_name' => $loan->name,
                        'amount' => $loan->loan_amount,
                        'date' => $loan->date
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('loans.index')->with('success', 'Loan updated successfully');
        } catch (\Exception $e) {

            DB::rollback();

            return redirect()->back()->with('error', 'An error occurred while updating the loan. Please try again.');
        }
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $loan = Loan::find($id);
        Transaction::where('trx_id', $loan->trx_id)->delete();
        $repayments = LoanRepayment::where('loan_id', $loan->id)->get();
        if ($repayments->count() > 0) {
            foreach ($repayments as $repayment) {
                Expense::where('trx_id', $repayment->trx_id)->delete();
                Transaction::where('trx_id', $repayment->trx_id)->delete();
                $repayment->delete();
            }
        }
        $loan->delete();

        return redirect()->route('loans.index')
            ->with('success', 'Loan deleted successfully');
    }
}
