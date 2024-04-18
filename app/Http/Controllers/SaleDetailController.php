<?php

namespace App\Http\Controllers;

use App\Models\SaleDetail;
use Illuminate\Http\Request;

/**
 * Class SaleDetailController
 * @package App\Http\Controllers
 */
class SaleDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $date1 = $request->input('date1');
        $date2 = $request->input('date2');
        $product_id = $request->input('product_id');

        $query = SaleDetail::with('sale');

        if ($request->filled('date1') && $request->filled('date2')) {
            $query->whereHas('sale', function ($query) use ($date1, $date2) {
                $query->whereBetween('date', [$date1, $date2])
                ->orderByDesc('date');
            });
        }

        if ($product_id) {
            $query->where('product_id', $product_id);
        }

        $saleDetails = $query->paginate(10);

        return view('sale-detail.index', compact('saleDetails'))
            ->with('i', (request()->input('page', 1) - 1) * $saleDetails->perPage());
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $saleDetail = new SaleDetail();
        return view('sale-detail.create', compact('saleDetail'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(SaleDetail::$rules);

        $saleDetail = SaleDetail::create($request->all());

        return redirect()->route('sale_details.index')
            ->with('success', 'SaleDetail created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $saleDetail = SaleDetail::find($id);
        return view('sale-detail.show', compact('saleDetail'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $saleDetail = SaleDetail::find($id);

        return view('sale-detail.edit', compact('saleDetail'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  SaleDetail $saleDetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SaleDetail $saleDetail)
    {
        request()->validate(SaleDetail::$rules);

        $saleDetail->update($request->all());

        return redirect()->route('sale_details.index')
            ->with('success', 'SaleDetail updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $saleDetail = SaleDetail::find($id)->delete();

        return redirect()->route('sale_details.index')
            ->with('success', 'SaleDetail deleted successfully');
    }
}
