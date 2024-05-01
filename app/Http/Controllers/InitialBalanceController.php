<?php

namespace App\Http\Controllers;

use App\Models\InitialBalance;
use Illuminate\Http\Request;

/**
 * Class InitialBalanceController
 * @package App\Http\Controllers
 */
class InitialBalanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $initialBalances = InitialBalance::paginate(10);

        return view('initial-balance.index', compact('initialBalances'))
            ->with('i', (request()->input('page', 1) - 1) * $initialBalances->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $initialBalance = new InitialBalance();
        return view('initial-balance.create', compact('initialBalance'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(InitialBalance::$rules);

        $initialBalance = InitialBalance::create($request->all());

        return redirect()->route('initial-balances.index')
            ->with('success', 'InitialBalance created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $initialBalance = InitialBalance::find($id);

        return view('initial-balance.show', compact('initialBalance'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $initialBalance = InitialBalance::find($id);

        return view('initial-balance.edit', compact('initialBalance'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  InitialBalance $initialBalance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InitialBalance $initialBalance)
    {
        request()->validate(InitialBalance::$rules);

        $initialBalance->update($request->all());

        return redirect()->route('initial-balances.index')
            ->with('success', 'InitialBalance updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $initialBalance = InitialBalance::find($id)->delete();

        return redirect()->route('initial-balances.index')
            ->with('success', 'InitialBalance deleted successfully');
    }
}
