<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Investment;
use App\Models\InvestmentRepayment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Class InvestmentController
 * @package App\Http\Controllers
 */
class InvestmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $investments = Investment::paginate(10);

        return view('investment.index', compact('investments'))
            ->with('i', (request()->input('page', 1) - 1) * $investments->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $investment = new Investment();
        return view('investment.create', compact('investment'));
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
            request()->validate(Investment::$rules);

            $data = $request->all();
            $data['trx_id'] = Str::uuid();

            $bankLoan = Investment::create($data);

            $account = Account::find($request->input('account_id'));

            // Create debit transaction
            $creditTransaction = Transaction::create([
                'account_id' => $request->input('account_id'),
                'account_name' => $account->name,
                'amount' => $bankLoan->loan_amount,
                'type' => 'credit',
                'reference_id' => $bankLoan->id,
                'date' => $bankLoan->date,
                'transaction_type' => 'investment',
                'user_id' => Auth::id(),
                'trx_id' => $bankLoan->trx_id
            ]);

            // Create credit transaction
            $debitTransaction = Transaction::create([
                'account_name' => $bankLoan->name,
                'amount' => $bankLoan->loan_amount,
                'type' => 'debit',
                'reference_id' => $bankLoan->id,
                'date' => $bankLoan->date,
                'transaction_type' => 'investment',
                'user_id' => Auth::id(),
                'trx_id' => $bankLoan->trx_id
            ]);

            // Commit the transaction
            DB::commit();

            return redirect()->route('investments.index')
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
        $investment = Investment::find($id);

        return view('investment.show', compact('investment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $investment = Investment::find($id);

        return view('investment.edit', compact('investment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Investment $investment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Investment $investment)
    {
        request()->validate(Investment::$rules);

        $investment->update($request->all());

        $creditTransaction = Transaction::where('reference_id', $investment->id)
            ->where('transaction_type', 'investment')
            ->where('type', 'credit')
            ->first();

        $creditTransaction->update([
            'amount' => $investment->loan_amount,
            'date' => $investment->date,
        ]);

        // Update credit transaction
        $debitTransaction = Transaction::where('reference_id', $investment->id)
            ->where('transaction_type', 'investment')
            ->where('type', 'debit')
            ->first();

        $debitTransaction->update([
            'amount' => $investment->loan_amount,
            'date' => $investment->date,
        ]);

        return redirect()->route('investments.index')
            ->with('success', 'Investment updated successfully');
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
            $investment = Investment::find($id);

            $repayments = InvestmentRepayment::where('investment__id', $investment->id)->get();

            if ($repayments->isNotEmpty()) {
                foreach ($repayments as $repayment) {
                    Income::where('trx_id', $repayment->trx_id)->delete();
                    Transaction::where('trx_id', $repayment->trx_id)->delete();
                    $repayment->delete();
                }
            }
            Transaction::where('trx_id', $investment->trx_id)->delete();
            // Delete the bank loan
            $investment->delete();

            // Commit the transaction
            DB::commit();

            return redirect()->route('investments.index')
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
