<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class AccountController
 * @package App\Http\Controllers
 */
class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accounts = Account::paginate(20);

        return view('account.index', compact('accounts'))
            ->with('i', (request()->input('page', 1) - 1) * $accounts->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $account = new Account();
        return view('account.create', compact('account'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Account::$rules);

        $account = Account::create($request->all());

        if ($account->starting_balance > 0){
            Transaction::create([
                'account_name' => $account->name,
                'amount' => $account->starting_balance,
                'transaction_type' => 'account_opening_balance',
                'type' => 'debit',
                'account_id' => $account->id,
                'user_id' => Auth::id(),
                'date' => $request->date,
            ]);
        }

        return redirect()->route('accounts.index')
            ->with('success', 'Account created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $account = Account::find($id);

        $transactions = Transaction::where('account_id',$id)
            ->orderBy('date','desc')
            ->paginate(30);

        $totalDebit = Transaction::where('account_id',$id)
            ->where('type','debit')
            ->sum('amount');
        $totalCredit = Transaction::where('account_id',$id)
            ->where('type','credit')
            ->sum('amount');

        return view('account.show', compact('account','transactions','totalDebit','totalCredit'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $account = Account::find($id);

        return view('account.edit', compact('account'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Account $account
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Account $account)
    {
        request()->validate(Account::$rules);

        $account->update($request->all());

        $debitTransaction = Transaction::where('transaction_type','account_opening_balance')
            ->where('account_id', $account->id)->first();
        if ($debitTransaction){
            if ($account->starting_balance > 0) {
                $debitTransaction->amount = $account->starting_balance;
                $debitTransaction->date = $request->date;
                $debitTransaction->save();
            }else{
                $debitTransaction->delete();
            }
        }else {
            if ($account->starting_balance > 0) {
                Transaction::create([
                    'account_name' => $account->name,
                    'account_id' => $account->id,
                    'amount' => $account->starting_balance,
                    'transaction_type' => 'account_opening_balance',
                    'type' => 'debit',
                    'user_id' => Auth::id(),
                    'date' => $request->date,
                ]);
            }
        }

        return redirect()->route('accounts.index')
            ->with('success', 'Account updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $account = Account::find($id)->delete();

        return redirect()->route('accounts.index')
            ->with('success', 'Account deleted successfully');
    }
}
