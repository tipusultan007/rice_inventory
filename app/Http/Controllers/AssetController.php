<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Asset;
use App\Models\Transaction;
use Illuminate\Http\Request;

/**
 * Class AssetController
 * @package App\Http\Controllers
 */
class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $assets = Asset::paginate(10);
        $accounts = Account::all();

        return view('asset.index', compact('assets','accounts'))
            ->with('i', (request()->input('page', 1) - 1) * $assets->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $asset = new Asset();
        $accounts = Account::all();
        return view('asset.create', compact('asset','accounts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Asset::$rules);

        $asset = Asset::create($request->all());

        Transaction::create([
            'account_id' => $request->input('account_id'),
            'amount' => $asset->value,
            'type' => 'debit',
            'reference_id' => $asset->id,
            'date' => $asset->date,
            'transaction_type' => 'asset',
        ]);

        return redirect()->route('asset.index')
            ->with('success', 'Asset created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $asset = Asset::find($id);

        return view('asset.show', compact('asset'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $asset = Asset::find($id);
        $accounts = Account::all();

        $transaction = Transaction::where('transaction_type','asset')
            ->where('reference_id', $asset->id)->first();

        return view('asset.edit', compact('asset','accounts','transaction'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Asset $asset
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Asset $asset)
    {
        request()->validate(Asset::$rules);

        $asset->update($request->all());

        $assetTransaction = Transaction::where('transaction_type','asset')
            ->where('reference_id', $asset->id)->first();

        if ($assetTransaction){
            $asset->amount = $asset->value;
            $asset->date = $asset->date;
            $asset->save();
        }

        return redirect()->route('asset.index')
            ->with('success', 'Asset updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $asset = Asset::find($id);

        $transaction = Transaction::where('transaction_type','asset')
            ->where('reference_id', $asset->id)->delete();

        $asset->delete();

        return redirect()->route('asset.index')
            ->with('success', 'Asset deleted successfully');
    }
}
