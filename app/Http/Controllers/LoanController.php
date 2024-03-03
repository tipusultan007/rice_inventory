<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Transaction;
use Illuminate\Http\Request;

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
        $transactions = Transaction::whereNotNull('loan_id')->orderByDesc('id')->paginate(10);
        return view('loan.transactions',compact('transactions'));
    }

    public function loanRepayment(Request $request)
    {

        $data = $request->all();

        if ($request->input('amount')>0) {
            Transaction::create([
                'account_id' => $request->input('account_id'),
                'amount' => $request->input('amount'),
                'type' => 'debit',
                'loan_id' => $request->input('loan_id'),
                'date' => $request->input('date'),
                'transaction_type' => 'loan_repayment',
            ]);

            $loan = Loan::find($request->input('loan_id'));
            $loan->balance -= $request->input('amount');
            $loan->save();
        }

        if ($request->input('loan_interest')>0)
        // Create a transaction for the balance transfer to the destination account
        {
            Transaction::create([
                'account_id' => $request->input('account_id'),
                'amount' => $request->input('loan_interest'),
                'type' => 'debit',
                'loan_id' => $request->input('loan_id'),
                'date' => $request->input('date'),
                'transaction_type' => 'loan_interest',
            ]);
        }


        return redirect()->back()->with('success','Loan payment successfully added!');
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
        request()->validate(Loan::$rules);

        $loan = Loan::create($request->all());
        $loan->balance = $loan->loan_amount;
        $loan->save();

        Transaction::create([
            'amount' => $loan->loan_amount,
            'type' => 'credit',
            'loan_id' => $loan->id,
            'reference_id' => $loan->id,
            'date' => $request->input('date'),
            'transaction_type' => 'loan_taken',
        ]);

        return redirect()->route('loans.index')
            ->with('success', 'Loan created successfully.');
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

        return view('loan.show', compact('loan'));
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

        return view('loan.edit', compact('loan'));
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
        request()->validate(Loan::$rules);

        $loan->update($request->all());

        $transaction = Transaction::where('loan_id',$loan->id)->first();
        if ($transaction){
            $transaction->amount = $loan->loan_amount;
            $transaction->date = $loan->date;
            $transaction->save();
        }

        return redirect()->route('loans.index')
            ->with('success', 'Loan updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $loan = Loan::find($id);
        Transaction::where('loan_id', $loan->id)->delete();
        $loan->delete();

        return redirect()->route('loans.index')
            ->with('success', 'Loan deleted successfully');
    }
}
