<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\BankLoan;
use App\Models\BankLoanRepayment;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Class BankLoanController
 * @package App\Http\Controllers
 */
class BankLoanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bankLoans = BankLoan::orderBy('date','asc')->paginate(50);

        return view('bank-loan.index', compact('bankLoans'))
            ->with('i', (request()->input('page', 1) - 1) * $bankLoans->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $bankLoan = new BankLoan();
        return view('bank-loan.create', compact('bankLoan'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Start a database transaction
        DB::beginTransaction();

        try {
            request()->validate(BankLoan::$rules);

            $data = $request->all();
            $data['trx_id'] = Str::uuid();
            $data['total_loan'] = $request->input('interest') + $request->input('loan_amount');

            $bankLoan = BankLoan::create($data);
            $bankLoan->total_loan = $bankLoan->interest + $bankLoan->loan_amount;
            $bankLoan->save();

            $account = Account::find($request->input('account_id'));

            // Create debit transaction
            $debitTransaction = Transaction::create([
                'account_id' => $request->input('account_id'),
                'account_name' => $account->name,
                'amount' => $bankLoan->loan_amount,
                'type' => 'debit',
                'reference_id' => $bankLoan->id,
                'date' => $bankLoan->date,
                'transaction_type' => 'bank_loan',
                'user_id' => Auth::id(),
                'trx_id' => $bankLoan->trx_id
            ]);

            $expense = Expense::create([
                'expense_category_id' => 18,
                'date' => $bankLoan->date,
                'description' => $bankLoan->name.' - সুদ',
                'amount' => $bankLoan->interest,
                'user_id' => Auth::id(),
                'trx_id' => $bankLoan->trx_id
            ]);

            $debitTransaction1 = Transaction::create([
                'account_name' => $expense->description,
                'amount' => $expense->amount,
                'type' => 'debit',
                'reference_id' => $expense->id,
                'date' => $expense->date,
                'transaction_type' => 'expense',
                'user_id' => Auth::id(),
                'trx_id' => $expense->trx_id,
            ]);

            $creditTransaction = Transaction::create([
                'account_name' => $bankLoan->name,
                'amount' => $bankLoan->total_loan,
                'type' => 'credit',
                'reference_id' => $bankLoan->id,
                'date' => $bankLoan->date,
                'transaction_type' => 'bank_loan',
                'user_id' => Auth::id(),
                'trx_id' => $bankLoan->trx_id
            ]);


            // Commit the transaction
            DB::commit();

            return redirect()->route('bank_loans.index')
                ->with('success', 'BankLoan created successfully.');
        } catch (\Exception $e) {
            // If an exception occurs, rollback the transaction
            DB::rollback();

            // You can handle the exception as per your application's logic
            return redirect()->back()
                ->with('error', 'Error occurred while creating the bank loan: ' . $e->getMessage());
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
        $bankLoan = BankLoan::find($id);
        $transactions = BankLoanRepayment::where('bank_loan_id', $bankLoan->id)->get();
        return view('bank-loan.show', compact('bankLoan','transactions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $bankLoan = BankLoan::find($id);

        return view('bank-loan.edit', compact('bankLoan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  BankLoan $bankLoan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BankLoan $bankLoan)
    {
        // Start a database transaction
        DB::beginTransaction();

        try {
            request()->validate(BankLoan::$rules);

            $data = $request->all();
            $data['total_loan'] = $request->input('interest') + $request->input('loan_amount');

            $bankLoan->update($data);

            // Update debit transaction
            $debitTransaction = Transaction::where('reference_id', $bankLoan->id)
                ->where('transaction_type', 'bank_loan')
                ->where('type', 'debit')
                ->first();

            $debitTransaction->update([
                'amount' => $bankLoan->loan_amount,
                'date' => $bankLoan->date,
            ]);


            // Update expense
            $expense = Expense::where('trx_id', $bankLoan->trx_id)->first();

            $expense->update([
                'date' => $bankLoan->date,
                'amount' => $bankLoan->interest,
            ]);

            // Update debit transaction for expense
            $debitTransaction1 = Transaction::where('reference_id', $expense->id)
                ->where('transaction_type', 'expense')
                ->where('type', 'debit')
                ->first();

            $debitTransaction1->update([
                'amount' => $expense->amount,
                'date' => $expense->date,
            ]);
            // Update credit transaction
            $creditTransaction = Transaction::where('reference_id', $bankLoan->id)
                ->where('transaction_type', 'bank_loan')
                ->where('type', 'credit')
                ->first();

            $creditTransaction->update([
                'account_no' => $bankLoan->name,
                'amount' => $bankLoan->total_loan,
                'date' => $bankLoan->date,
            ]);
            // Commit the transaction
            DB::commit();

            return redirect()->route('bank_loans.index')
                ->with('success', 'BankLoan updated successfully.');
        } catch (\Exception $e) {
            // If an exception occurs, rollback the transaction
            DB::rollback();

            // You can handle the exception as per your application's logic
            return redirect()->back()
                ->with('error', 'Error occurred while updating the bank loan: ' . $e->getMessage());
        }
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        // Start a database transaction
        DB::beginTransaction();

        try {
            $bankLoan = BankLoan::find($id);

            $repayments = BankLoanRepayment::where('bank_loan_id', $bankLoan->id)->get();
            Expense::where('trx_id', $bankLoan->trx_id)->delete();
            if ($repayments->isNotEmpty()) {
                foreach ($repayments as $repayment) {
                    Income::where('trx_id', $repayment->trx_id)->delete();
                    Transaction::where('trx_id', $repayment->trx_id)->delete();
                    $repayment->delete();
                }
            }

            Transaction::where('trx_id', $bankLoan->trx_id)->delete();

            // Delete the bank loan
            $bankLoan->delete();

            // Commit the transaction
            DB::commit();

            return redirect()->route('bank_loans.index')
                ->with('success', 'BankLoan deleted successfully');
        } catch (\Exception $e) {
            // If an exception occurs, rollback the transaction
            DB::rollback();

            // You can handle the exception as per your application's logic
            return redirect()->back()
                ->with('error', 'Error occurred while deleting the bank loan: ' . $e->getMessage());
        }
    }
}
