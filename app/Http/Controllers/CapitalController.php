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
 * Class CapitalController
 * @package App\Http\Controllers
 */
class CapitalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $capitals = Capital::paginate(10);

        return view('capital.index', compact('capitals'))
            ->with('i', (request()->input('page', 1) - 1) * $capitals->perPage());
    }

    public function capitalTransactions()
    {
        $transactions = CapitalWithdraw::whereNotNull('capital_id')->orderByDesc('id')->paginate(10);
        return view('capital.transactions',compact('transactions'));
    }

    public function capitalWithdraw(Request $request)
    {
        $request->validate([
            'amount' => ['required_without:capital_profit'],
            'capital_profit' => ['required_without:amount'],
            'account_id' => 'required',
            'capital_id' => 'required',
            'date' => 'required|date',
        ]);

        // Start a database transaction
        DB::beginTransaction();

        try {
            $data = $request->all();
            $data['trx_id'] = Str::uuid();

            if ($request->input('amount') > 0) {
                Transaction::create([
                    'account_id' => $request->input('account_id'),
                    'amount' => $request->input('amount'),
                    'type' => 'debit',
                    'capital_id' => $request->input('capital_id'),
                    'reference_id' => $request->input('capital_id'),
                    'date' => $request->input('date'),
                    'transaction_type' => 'capital_withdraw',
                    'user_id' => Auth::id()
                ]);

                Transaction::create([
                    'amount' => $request->input('amount'),
                    'type' => 'credit',
                    'capital_id' => $request->input('capital_id'),
                    'date' => $request->input('date'),
                    'transaction_type' => 'capital_withdraw',
                    'reference_id' => $request->input('capital_id'),
                    'user_id' => Auth::id()
                ]);

                $capital = Capital::find($request->input('capital_id'));
                $capital->balance -= $request->input('amount');
                $capital->save();
            }

            if ($request->input('capital_profit') > 0) {
                Transaction::create([
                    'account_id' => $request->input('account_id'),
                    'amount' => $request->input('capital_profit'),
                    'type' => 'debit',
                    'capital_id' => $request->input('capital_id'),
                    'date' => $request->input('date'),
                    'transaction_type' => 'capital_profit',
                    'reference_id' => $request->input('capital_id'),
                    'user_id' => Auth::id()
                ]);

                Transaction::create([
                    'amount' => $request->input('capital_profit'),
                    'type' => 'credit',
                    'capital_id' => $request->input('capital_id'),
                    'date' => $request->input('date'),
                    'transaction_type' => 'capital_profit',
                    'reference_id' => $request->input('capital_id'),
                    'user_id' => Auth::id()
                ]);
            }

            // Commit the transaction
            DB::commit();

            return redirect()->back()->with('success', 'Capital Transaction successfully added!');
        } catch (\Exception $e) {
            // Something went wrong, rollback the transaction
            DB::rollback();

            return redirect()->back()->with('error', 'An error occurred. Please try again.');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $capital = new Capital();
        return view('capital.create', compact('capital'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate(Capital::$rules);

        DB::beginTransaction();

        try {
            $data = $request->all();
            $data['trx_id'] = Str::uuid();
            $capital = Capital::create($data);
            $capital->update(['balance' => $capital->amount]);

            $account = Account::find($request->input('account_id'));
            Transaction::create([
                'account_id' => $request->input('account_id'),
                'account_name' => $account->name,
                'amount' => $capital->amount,
                'type' => 'debit',
                'reference_id' => $capital->id,
                'date' => $capital->date,
                'transaction_type' => 'capital',
                'user_id' => Auth::id(),
                'trx_id' => $capital->trx_id
            ]);

            Transaction::create([
                'account_name' => $capital->name,
                'amount' => $capital->amount,
                'type' => 'credit',
                'reference_id' => $capital->id,
                'date' => $capital->date,
                'transaction_type' => 'capital',
                'user_id' => Auth::id(),
                'trx_id' => $capital->trx_id
            ]);

            // Commit the transaction
            DB::commit();

            return redirect()->route('capitals.index')->with('success', 'Capital created successfully.');
        } catch (\Exception $e) {
            // Something went wrong, rollback the transaction
            DB::rollback();

            \Log::error($e);

            return redirect()->back()->with('error', 'An error occurred while creating capital. Please try again.'.$e);
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
        $capital = Capital::find($id);
        $transactions = CapitalWithdraw::where('capital_id', $capital->id)->get();
        return view('capital.show', compact('capital','transactions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $capital = Capital::find($id);

        $creditTransaction = Transaction::where('reference_id',$capital->id)
            ->where('transaction_type','capital')
            ->where('type','debit')
            ->first();

        return view('capital.edit', compact('capital'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Capital $capital
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Capital $capital)
    {
        $validatedData = $request->validate(Capital::$rules);

        // Start a database transaction
        DB::beginTransaction();

        try {
            $data = $request->all();
            $capital->update($data);

            $capital->update(['balance' => $capital->amount]);

            $transactions = Transaction::where('trx_id', $capital->trx_id)
                ->where('transaction_type', 'capital')
                ->whereIn('type', ['credit', 'debit'])
                ->get();

            foreach ($transactions as $transaction) {
                if ($transaction->type === 'debit') {
                    $account = Account::find($request->input('account_id'));
                    $transaction->update([
                        'amount' => $capital->amount,
                        'account_id' => $request->input('account_id'),
                        'name' => $account->name,
                        'date' => $capital->date
                    ]);
                } elseif ($transaction->type === 'credit') {

                    $transaction->update([
                        'amount' => $capital->amount,
                        'account_name' => $capital->name,
                        'date' => $capital->date
                    ]);
                }
            }

            // Commit the transaction
            DB::commit();

            return redirect()->route('capitals.index')->with('success', 'Capital updated successfully');
        } catch (\Exception $e) {
            // Something went wrong, rollback the transaction
            DB::rollback();

            return redirect()->back()->with('error', 'An error occurred while updating capital. Please try again.');
        }
    }


    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $capital = Capital::find($id);
        Transaction::where('trx_id', $capital->trx_id)->delete();
        $capital_withdraws = CapitalWithdraw::where('capital_id', $capital->id)->get();
        if ($capital_withdraws){
            foreach ($capital_withdraws as $withdraw){
                Expense::where('trx_id', $withdraw->trx_id)->delete();
                Transaction::where('trx_id', $withdraw->trx_id)->delete();
                $withdraw->delete();
            }
        }
        $capital->delete();

        return redirect()->route('capitals.index')
            ->with('success', 'Capital deleted successfully');
    }
}
