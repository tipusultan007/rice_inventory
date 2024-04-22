<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Income;
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
       // $expenses = Expense::where('expense_category_id', $id)->paginate(50);

        return view('expense-category.show', compact('expenseCategory'));
    }

    public function dataExpenseByCategory(Request $request)
    {
        $columns = array(
            0 =>'date',
        );

        $date1 = $request->input('date1'); // Start date of range
        $date2 = $request->input('date2'); // End date of range

        $query = Expense::where('expense_category_id', $request->id);

        // Apply date range filtering if dates are provided
        if (!empty($date1) && !empty($date2)) {
            $query->whereBetween('date', [$date1, $date2]);
        }

        $totalData = $query->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $posts = $query->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =  $query->where('description', 'LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered = $query->where('description', 'LIKE',"%{$search}%")
                ->count();
        }

        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData['id'] = $post->id;
                $nestedData['date'] = date('d/m/Y',strtotime($post->date));
                $nestedData['description'] = $post->description??'-';
                $nestedData['amount'] = $post->amount;
                $data[] = $nestedData;

            }
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        echo json_encode($json_data);
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
