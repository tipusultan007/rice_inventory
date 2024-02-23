<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

/**
 * Class ExpenseCategoryController
 * @package App\Http\Controllers
 */
class ExpenseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $expenseCategories = ExpenseCategory::paginate(10);

        return view('expense-category.index', compact('expenseCategories'))
            ->with('i', (request()->input('page', 1) - 1) * $expenseCategories->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $expenseCategory = new ExpenseCategory();
        return view('expense-category.create', compact('expenseCategory'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(ExpenseCategory::$rules);

        $expenseCategory = ExpenseCategory::create($request->all());

        return redirect()->route('expense_categories.index')
            ->with('success', 'ExpenseCategory created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $expenseCategory = ExpenseCategory::find($id);

        return view('expense-category.show', compact('expenseCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $expenseCategory = ExpenseCategory::find($id);

        return view('expense-category.edit', compact('expenseCategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  ExpenseCategory $expenseCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExpenseCategory $expenseCategory)
    {
        request()->validate(ExpenseCategory::$rules);

        $expenseCategory->update($request->all());

        return redirect()->route('expense_categories.index')
            ->with('success', 'ExpenseCategory updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $expenseCategory = ExpenseCategory::find($id)->delete();

        return redirect()->route('expense_categories.index')
            ->with('success', 'ExpenseCategory deleted successfully');
    }
}
