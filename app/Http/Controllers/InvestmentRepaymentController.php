<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Income;
use App\Models\Investment;
use App\Models\InvestmentRepayment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Class InvestmentRepaymentController
 * @package App\Http\Controllers
 */
class InvestmentRepaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $investmentRepayments = InvestmentRepayment::paginate(10);

        return view('investment-repayment.index', compact('investmentRepayments'))
            ->with('i', (request()->input('page', 1) - 1) * $investmentRepayments->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $investmentRepayment = new InvestmentRepayment();
        return view('investment-repayment.create', compact('investmentRepayment'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Begin a database transaction
        DB::beginTransaction();

        try {
            // Validate the incoming request
            request()->validate(InvestmentRepayment::$rules);

            $data = $request->all();
            $data['trx_id'] = Str::uuid();
            $data['user_id'] = Auth::id();

            // Create InvestmentRepayment record
            $investmentRepayment = InvestmentRepayment::create($data);

            if ($request->input('amount') > 0) {
                $account = Account::find($request->input('account_id'));

                // Create Debit Transaction
                $debitTransaction = Transaction::create([
                    'account_id' => $request->input('account_id'),
                    'account_name' => $account->name,
                    'amount' => $request->input('amount'),
                    'type' => 'debit',
                    'reference_id' => $investmentRepayment->id,
                    'date' => $request->input('date'),
                    'transaction_type' => 'investment_repayment',
                    'user_id' => Auth::id(),
                    'trx_id' => $investmentRepayment->trx_id
                ]);

                // Create Credit Transaction
                $creditTransaction = Transaction::create([
                    'amount' => $request->input('amount'),
                    'account_name' => $investmentRepayment->investment->name,
                    'type' => 'credit',
                    'date' => $request->input('date'),
                    'transaction_type' => 'investment_repayment',
                    'reference_id' => $investmentRepayment->id,
                    'user_id' => Auth::id(),
                    'trx_id' => $investmentRepayment->trx_id
                ]);

                // Update investment balance
                $loan = Investment::find($request->input('investment_id'));
                $investmentRepayment->balance = $loan->balance;
                $investmentRepayment->save();
            }

            if ($request->input('interest') > 0) {
                // Create Income record
                $income = Income::create([
                    'income_category_id' => 2,
                    'date' => $investmentRepayment->date,
                    'description' => $investmentRepayment->investment->name.' - মুনাফা',
                    'amount' => $investmentRepayment->interest,
                    'user_id' => Auth::id(),
                    'trx_id' => $investmentRepayment->trx_id
                ]);

                $account = Account::find($request->input('account_id'));

                // Create Debit Transaction for income
                $debitTransaction1 = Transaction::create([
                    'account_id' => $request->input('account_id'),
                    'account_name' => $account->name,
                    'amount' => $income->amount,
                    'type' => 'debit',
                    'reference_id' => $income->id,
                    'date' => $income->date,
                    'transaction_type' => 'income',
                    'user_id' => Auth::id(),
                    'trx_id' => $income->trx_id,
                ]);

                // Create Credit Transaction for income
                $creditTransaction1 = Transaction::create([
                    'account_name' => $income->description,
                    'amount' => $income->amount,
                    'type' => 'credit',
                    'reference_id' => $income->id,
                    'date' => $income->date,
                    'transaction_type' => 'income',
                    'user_id' => Auth::id(),
                    'trx_id' => $income->trx_id,
                ]);

                // Update investmentRepayment balance and total_interest
                $loan = Investment::find($request->input('investment_id'));
                $investmentRepayment->balance = $loan->balance;
                $investmentRepayment->total_interest = $loan->total_interest;
                $investmentRepayment->save();
            }

            // Commit the transaction
            DB::commit();

            return redirect()->route('investment_repayments.index')
                ->with('success', 'InvestmentRepayment created successfully.');
        } catch (\Exception $e) {
            // If an exception occurs, rollback the transaction
            DB::rollBack();

            return redirect()->back()
                ->with('error', $e->getMessage());
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
        $investmentRepayment = InvestmentRepayment::find($id);

        return view('investment-repayment.show', compact('investmentRepayment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $investmentRepayment = InvestmentRepayment::find($id);

        return view('investment-repayment.edit', compact('investmentRepayment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  InvestmentRepayment $investmentRepayment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InvestmentRepayment $investmentRepayment)
    {
        request()->validate(InvestmentRepayment::$rules);

        $investmentRepayment->update($request->all());

        return redirect()->route('investment_repayments.index')
            ->with('success', 'InvestmentRepayment updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $investmentRepayment = InvestmentRepayment::find($id);
        Income::where('trx_id', $investmentRepayment->trx_id)->delete();
        Transaction::where('trx_id', $investmentRepayment->trx_id)->delete();
        $investmentRepayment->delete();

        return redirect()->route('investment_repayments.index')
            ->with('success', 'InvestmentRepayment deleted successfully');
    }
}
