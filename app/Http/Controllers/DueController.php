<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

/**
 * Class DueController
 * @package App\Http\Controllers
 */
class DueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dues = Payment::with('customer')->whereNotNull('customer_id')->paginate(10);

        return view('due.index', compact('dues'))
            ->with('i', (request()->input('page', 1) - 1) * $dues->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $due = new Payment();
        return view('due.create', compact('due'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Payment::$rules);

        $due = Payment::create($request->all());

        return redirect()->route('dues.index')
            ->with('success', 'Due created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $due = Payment::find($id);

        return view('due.show', compact('due'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $due = Payment::find($id);

        return view('due.edit', compact('due'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Payment $due
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $due)
    {
        request()->validate(Payment::$rules);

        $due->update($request->all());

        return redirect()->route('dues.index')
            ->with('success', 'Due updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $due = Payment::find($id)->delete();

        return redirect()->route('dues.index')
            ->with('success', 'Due deleted successfully');
    }
}
