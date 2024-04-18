<?php

namespace App\Http\Controllers;

use App\Models\IncomeCategory;
use Illuminate\Http\Request;

/**
 * Class IncomeCategoryController
 * @package App\Http\Controllers
 */
class IncomeCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $incomeCategories = IncomeCategory::paginate(10);

        return view('income-category.index', compact('incomeCategories'))
            ->with('i', (request()->input('page', 1) - 1) * $incomeCategories->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $incomeCategory = new IncomeCategory();
        return view('income-category.create', compact('incomeCategory'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(IncomeCategory::$rules);

        $incomeCategory = IncomeCategory::create($request->all());

        return redirect()->route('income_categories.index')
            ->with('success', 'IncomeCategory created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $incomeCategory = IncomeCategory::find($id);

        return view('income-category.show', compact('incomeCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $incomeCategory = IncomeCategory::find($id);

        return view('income-category.edit', compact('incomeCategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  IncomeCategory $incomeCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, IncomeCategory $incomeCategory)
    {
        request()->validate(IncomeCategory::$rules);

        $incomeCategory->update($request->all());

        return redirect()->route('income_categories.index')
            ->with('success', 'IncomeCategory updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $incomeCategory = IncomeCategory::find($id)->delete();

        return redirect()->route('income_categories.index')
            ->with('success', 'IncomeCategory deleted successfully');
    }
}
