<?php

namespace App\Http\Controllers;

use App\Models\SaleReturn;
use Illuminate\Http\Request;

/**
 * Class SaleReturnController
 * @package App\Http\Controllers
 */
class SaleReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $saleReturns = SaleReturn::paginate(10);

        return view('sale-return.index', compact('saleReturns'))
            ->with('i', (request()->input('page', 1) - 1) * $saleReturns->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $saleReturn = new SaleReturn();
        return view('sale-return.create', compact('saleReturn'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(SaleReturn::$rules);

        $saleReturn = SaleReturn::create($request->all());

        return redirect()->route('sale_returns.index')
            ->with('success', 'SaleReturn created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $saleReturn = SaleReturn::find($id);

        return view('sale-return.show', compact('saleReturn'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $saleReturn = SaleReturn::find($id);

        return view('sale-return.edit', compact('saleReturn'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  SaleReturn $saleReturn
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SaleReturn $saleReturn)
    {
        request()->validate(SaleReturn::$rules);

        $saleReturn->update($request->all());

        return redirect()->route('sale_returns.index')
            ->with('success', 'SaleReturn updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $saleReturn = SaleReturn::find($id)->delete();

        return redirect()->route('sale_returns.index')
            ->with('success', 'SaleReturn deleted successfully');
    }
}
