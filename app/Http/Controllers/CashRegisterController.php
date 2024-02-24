<?php

namespace App\Http\Controllers;

use App\Models\CashRegister;
use Illuminate\Http\Request;

/**
 * Class CashRegisterController
 * @package App\Http\Controllers
 */
class CashRegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cashRegisters = CashRegister::paginate(10);

        return view('cash-register.index', compact('cashRegisters'))
            ->with('i', (request()->input('page', 1) - 1) * $cashRegisters->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cashRegister = new CashRegister();
        return view('cash-register.create', compact('cashRegister'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(CashRegister::$rules);

        $cashRegister = CashRegister::create($request->all());

        return redirect()->route('cash_registers.index')
            ->with('success', 'CashRegister created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cashRegister = CashRegister::find($id);

        return view('cash-register.show', compact('cashRegister'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cashRegister = CashRegister::find($id);

        return view('cash-register.edit', compact('cashRegister'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  CashRegister $cashRegister
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CashRegister $cashRegister)
    {
        request()->validate(CashRegister::$rules);

        $cashRegister->update($request->all());

        return redirect()->route('cash_registers.index')
            ->with('success', 'CashRegister updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $cashRegister = CashRegister::find($id)->delete();

        return redirect()->route('cash_registers.index')
            ->with('success', 'CashRegister deleted successfully');
    }
}
