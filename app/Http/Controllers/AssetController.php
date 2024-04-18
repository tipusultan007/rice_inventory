<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Asset;
use App\Models\AssetSell;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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

        $data = $request->all();
        $data['trx_id'] = Str::uuid();
        $asset = Asset::create($data);

        $account = Account::find($request->input('account_id'));
        $creditTransaction = Transaction::create([
            'account_id' => $request->input('account_id'),
            'account_name' => $account->name,
            'amount' => $asset->value,
            'type' => 'credit',
            'reference_id' => $asset->id,
            'date' => $asset->date,
            'transaction_type' => 'asset',
            'user_id' => Auth::id(),
            'trx_id' => $asset->trx_id
        ]);

        $debitTransaction = Transaction::create([
            'account_name' => $asset->name,
            'amount' => $asset->value,
            'type' => 'debit',
            'reference_id' => $asset->id,
            'date' => $asset->date,
            'transaction_type' => 'asset',
            'user_id' => Auth::id(),
            'trx_id' => $asset->trx_id
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
        $assetSells = AssetSell::where('asset_id',$asset->id)->orderBy('id','desc')->get();

        return view('asset.show', compact('asset','assetSells'));
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

        $creditTransaction = Transaction::where('transaction_type','asset')
            ->where('type','credit')
            ->where('reference_id', $asset->id)->first();

        $debitTransaction = Transaction::where('transaction_type','asset')
            ->where('type','debit')
            ->where('reference_id', $asset->id)->first();

        $account = Account::find($request->input('account_id'));

        if ($debitTransaction){
            $debitTransaction->amount = $asset->value;
            $debitTransaction->date = $asset->date;
            $debitTransaction->save();
        }

        if ($creditTransaction){
            $creditTransaction->account_id = $request->input('account_id');
            $creditTransaction->account_name = $account->name;
            $creditTransaction->amount = $asset->value;
            $creditTransaction->date = $asset->date;
            $creditTransaction->save();
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

        $transaction = Transaction::where('trx_id',$asset->trx_id)->delete();

        $sells = AssetSell::where('asset_id',$asset->id)->get();
        if ($sells->count() > 0){
            foreach ($sells as $sell){
                Expense::where('trx_id', $sell->trx_id)->delete();
                Income::where('trx_id', $sell->trx_id)->delete();
                Transaction::where('trx_id', $sell->trx_id)->delete();
                $sell->delete();
            }
        }

        $asset->delete();

        return redirect()->route('asset.index')
            ->with('success', 'Asset deleted successfully');
    }
}
