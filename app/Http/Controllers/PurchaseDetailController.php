<?php

namespace App\Http\Controllers;

use App\Models\PurchaseDetail;
use Illuminate\Http\Request;

/**
 * Class PurchaseDetailController
 * @package App\Http\Controllers
 */
class PurchaseDetailController extends Controller
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

        $query = PurchaseDetail::with('purchase');

        if ($request->filled('date1') && $request->filled('date2')) {
            $query->whereHas('purchase', function ($query) use ($date1, $date2) {
                $query->whereBetween('date', [$date1, $date2])
                    ->orderByDesc('date');
            });
        }

        if ($product_id) {
            $query->where('product_id', $product_id);
        }

        $purchaseDetails = $query->paginate(10);

        return view('purchase-detail.index', compact('purchaseDetails'))
            ->with('i', (request()->input('page', 1) - 1) * $purchaseDetails->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $purchaseDetail = new PurchaseDetail();
        return view('purchase-detail.create', compact('purchaseDetail'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(PurchaseDetail::$rules);

        $purchaseDetail = PurchaseDetail::create($request->all());

        return redirect()->route('purchase_details.index')
            ->with('success', 'PurchaseDetail created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $purchaseDetail = PurchaseDetail::find($id);

        return view('purchase-detail.show', compact('purchaseDetail'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $purchaseDetail = PurchaseDetail::find($id);

        return view('purchase-detail.edit', compact('purchaseDetail'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  PurchaseDetail $purchaseDetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PurchaseDetail $purchaseDetail)
    {
        request()->validate(PurchaseDetail::$rules);

        $purchaseDetail->update($request->all());

        return redirect()->route('purchase_details.index')
            ->with('success', 'PurchaseDetail updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $purchaseDetail = PurchaseDetail::find($id)->delete();

        return redirect()->route('purchase_details.index')
            ->with('success', 'PurchaseDetail deleted successfully');
    }
}
