<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\BankLoan;
use App\Models\BankLoanRepayment;
use App\Models\Income;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Class BankLoanRepaymentController
 * @package App\Http\Controllers
 */
class BankLoanRepaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bankLoanRepayments = BankLoanRepayment::paginate(10);

        return view('bank-loan-repayment.index', compact('bankLoanRepayments'))
            ->with('i', (request()->input('page', 1) - 1) * $bankLoanRepayments->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $bankLoanRepayment = new BankLoanRepayment();
        return view('bank-loan-repayment.create', compact('bankLoanRepayment'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->all();

            $data['trx_id'] = Str::uuid();
            $data['user_id'] = Auth::id();
            $loanRepayment = BankLoanRepayment::create($data);

            // Create debit transaction
            $debitTransaction = Transaction::create([
                'amount' => $request->input('amount') + $request->input('grace'),
                'account_name' => $loanRepayment->bankLoan->name,
                'type' => 'debit',
                'date' => $request->input('date'),
                'transaction_type' => 'bank_loan_repayment',
                'reference_id' => $loanRepayment->id,
                'user_id' => Auth::id(),
                'trx_id' => $loanRepayment->trx_id
            ]);

            if ($request->input('amount') > 0) {
                $account = Account::find($request->input('account_id'));
                $creditTransaction = Transaction::create([
                    'account_id' => $request->input('account_id'),
                    'account_name' => $account->name,
                    'amount' => $request->input('amount'),
                    'type' => 'credit',
                    'reference_id' => $loanRepayment->id,
                    'date' => $request->input('date'),
                    'transaction_type' => 'bank_loan_repayment',
                    'user_id' => Auth::id(),
                    'trx_id' => $loanRepayment->trx_id
                ]);
            }

            // Handle grace amount entry
            if ($request->input('grace') > 0) {
                $income = Income::create([
                    'income_category_id' => 3,
                    'description' => $loanRepayment->bankLoan->name,
                    'date' => $request->input('date'),
                    'amount' => $request->input('grace'),
                    'user_id' => Auth::id(),
                    'trx_id' => $loanRepayment->trx_id
                ]);

                $creditTransaction1 = Transaction::create([
                    'account_name' => $loanRepayment->bankLoan->name . ' - Rebate',
                    'amount' => $request->input('grace'),
                    'type' => 'credit',
                    'reference_id' => $loanRepayment->id,
                    'date' => $request->input('date'),
                    'transaction_type' => 'income',
                    'user_id' => Auth::id(),
                    'trx_id' => $loanRepayment->trx_id
                ]);
            }

            // Update loan balance
            $loan = BankLoan::find($request->input('bank_loan_id'));
            $loanRepayment->balance = $loan->balance;
            $loanRepayment->save();

            DB::commit();

            return redirect()->route('bank_loan_repayments.index')
                ->with('success', 'BankLoanRepayment created successfully.');
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
        $bankLoanRepayment = BankLoanRepayment::find($id);

        return view('bank-loan-repayment.show', compact('bankLoanRepayment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $bankLoanRepayment = BankLoanRepayment::find($id);

        return view('bank-loan-repayment.edit', compact('bankLoanRepayment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param BankLoanRepayment $bankLoanRepayment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BankLoanRepayment $loanRepayment)
    {
        DB::beginTransaction();

        try {
            $data = $request->validate(BankLoanRepayment::$rules);

            $loanRepayment->update($data);

            $debitTransaction = Transaction::where('reference_id', $loanRepayment->id)
                ->where('transaction_type', 'bank_loan_repayment')
                ->where('type', 'debit')
                ->update([
                    'amount' => $request->input('amount') + $request->input('grace'),
                    'date' => $request->input('date')
                ]);

            if ($request->input('amount') > 0) {
                $creditTransaction = Transaction::where('reference_id', $loanRepayment->id)
                    ->where('transaction_type', 'bank_loan_repayment')
                    ->where('type', 'credit')->first();

                if ($creditTransaction) {
                    $creditTransaction->update([
                        'amount' => $request->input('amount'),
                        'date' => $request->input('date')
                    ]);
                } else {
                    $account = Account::find($request->input('account_id'));
                    $creditTransaction = Transaction::create([
                        'account_id' => $request->input('account_id'),
                        'account_name' => $account->name,
                        'amount' => $request->input('amount'),
                        'type' => 'credit',
                        'reference_id' => $loanRepayment->id,
                        'date' => $request->input('date'),
                        'transaction_type' => 'bank_loan_repayment',
                        'user_id' => Auth::id(),
                        'trx_id' => $loanRepayment->trx_id
                    ]);
                }
            } else {
                Transaction::where('reference_id', $loanRepayment->id)
                    ->where('transaction_type', 'bank_loan_repayment')
                    ->where('type', 'credit')->delete();
            }


            if ($request->input('grace') > 0) {
                $income = Income::where('trx_id', $loanRepayment->trx_id)->first();
                $income->update([
                    'amount' => $request->input('grace'),
                    'date' => $request->input('date')
                ]);
                $incomeTransaction = Transaction::where('reference_id', $loanRepayment->id)
                    ->where('transaction_type', 'income')
                    ->first();
                $incomeTransaction->update([
                    'amount' => $request->input('grace'),
                    'date' => $request->input('date')
                ]);
            } else {
                Income::where('trx_id', $loanRepayment->trx_id)->delete();

                Transaction::where('reference_id', $loanRepayment->id)
                    ->where('transaction_type', 'income')
                    ->delete();
            }


            $loan = BankLoan::find($request->input('bank_loan_id'));
            $loanRepayment->balance = $loan->balance;
            $loanRepayment->save();

            DB::commit();

            return redirect()->route('bank_loan_repayments.index')
                ->with('success', 'BankLoanRepayment updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred. Transaction rolled back.');
        }
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $bankLoanRepayment = BankLoanRepayment::find($id);

            // Delete related income entries
            Income::where('trx_id', $bankLoanRepayment->trx_id)->delete();

            // Delete related transaction entries
            Transaction::where('trx_id', $bankLoanRepayment->trx_id)->delete();

            // Delete the BankLoanRepayment
            $bankLoanRepayment->delete();

            DB::commit();

            return redirect()->route('bank_loan_repayments.index')
                ->with('success', 'BankLoanRepayment deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred. Transaction rolled back.');
        }
    }
}
