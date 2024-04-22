<?php

namespace App\Http\Controllers;

use App\Models\Income;
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
    public function dataIncomeByCategory(Request $request)
    {
        $columns = array(
            0 =>'date',
        );

        $date1 = $request->input('date1'); // Start date of range
        $date2 = $request->input('date2'); // End date of range

        $query = Income::where('income_category_id', $request->id);

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
