<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AssetSell;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Class AssetSellController
 * @package App\Http\Controllers
 */
class AssetSellController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $assetSells = AssetSell::orderBy('id','desc')->paginate(10);

        return view('asset-sell.index', compact('assetSells'))
            ->with('i', (request()->input('page', 1) - 1) * $assetSells->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $assetSell = new AssetSell();
        return view('asset-sell.create', compact('assetSell'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(AssetSell::$rules);

        try {
            DB::beginTransaction();

            $data = $request->all();
            $data['trx_id'] = Str::uuid();

            $assetSell = AssetSell::create($data);
            $assetSell->balance = $assetSell->asset->balance;
            $assetSell->save();

            $account = Account::find($request->input('account_id'));
            $debitTransaction = Transaction::create([
                'account_id' => $request->input('account_id'),
                'account_name' => $account->name,
                'amount' => $assetSell->sale_price,
                'reference_id' => $assetSell->id,
                'transaction_type' => 'asset_sell',
                'type' => 'debit',
                'trx_id' => $assetSell->trx_id,
                'user_id' => Auth::id(),
                'date' => $assetSell->date
            ]);

            $creditTransaction = Transaction::create([
                'account_name' => 'সম্পদ',
                'amount' => $assetSell->purchase_price,
                'reference_id' => $assetSell->id,
                'transaction_type' => 'asset',
                'type' => 'credit',
                'trx_id' => $assetSell->trx_id,
                'user_id' => Auth::id(),
                'date' => $assetSell->date
            ]);

            if ($assetSell->sale_price > $assetSell->purchase_price){
                $income = Income::create([
                    'income_category_id' => 5,
                    'amount'=> $assetSell->sale_price - $assetSell->purchase_price,
                    'trx_id' => $assetSell->trx_id,
                    'date' => $assetSell->date,
                    'user_id' => Auth::id()
                ]);

                $creditTransaction = Transaction::create([
                    'account_name' => 'সম্পদ হতে প্রফিট',
                    'amount' => $income->amount,
                    'reference_id' => $income->id,
                    'transaction_type' => 'profit_from_asset',
                    'type' => 'credit',
                    'trx_id' => $assetSell->trx_id,
                    'user_id' => Auth::id(),
                    'date' => $assetSell->date
                ]);

            } elseif ($assetSell->purchase_price > $assetSell->sale_price){
                $expense = Expense::create([
                    'expense_category_id' => 22,
                    'amount'=> $assetSell->purchase_price - $assetSell->sale_price,
                    'trx_id' => $assetSell->trx_id,
                    'date' => $assetSell->date,
                    'user_id' => Auth::id()
                ]);
                $debitTransaction = Transaction::create([
                    'account_name' => 'সম্পদ হতে ক্ষতি',
                    'amount' => $expense->amount,
                    'reference_id' => $expense->id,
                    'transaction_type' => 'loss_at_asset',
                    'type' => 'debit',
                    'trx_id' => $assetSell->trx_id,
                    'user_id' => Auth::id(),
                    'date' => $assetSell->date
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to create AssetSell: ' . $e->getMessage());
        }

        return redirect()->route('asset_sells.index')->with('success', 'AssetSell created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $assetSell = AssetSell::find($id);

        return view('asset-sell.show', compact('assetSell'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $assetSell = AssetSell::find($id);

        return view('asset-sell.edit', compact('assetSell'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  AssetSell $assetSell
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AssetSell $assetSell)
    {
        //request()->validate(AssetSell::$rules);
        try {
            DB::beginTransaction();

            $data = $request->all();


            $assetSell->update($data);
            $assetSell->balance = $assetSell->asset->balance;
            $assetSell->save();

            $debitTransacion = Transaction::where('trx_id', $assetSell->trx_id)
                ->where('transaction_type','asset_sell')
                ->where('type','debit')->first();

            $debitTransacion->update([
                'amount' => $assetSell->sale_price,
                'date' => $assetSell->date
            ]);

            $creditTransaction = Transaction::where('trx_id', $assetSell->trx_id)
                ->where('transaction_type','asset')
                ->where('type','credit')->first();

            $creditTransaction->update([
                'amount' => $assetSell->purchase_price,
                'date' => $assetSell->date
            ]);

            $income = Income::where('trx_id', $assetSell->trx_id)->first();
            if ($income){
                if ($assetSell->sale_price > $assetSell->purchase_price){
                    $income->update([
                        'amount'=> $assetSell->sale_price - $assetSell->purchase_price,
                        'date' => $assetSell->date,
                    ]);

                    $creditTransaction = Transaction::where('reference_id',$income->id)
                        ->where('transaction_type','profit_from_asset')
                        ->update([
                            'amount' => $income->amount,
                            'date' => $assetSell->date
                        ]);

                } else{
                    Transaction::where('reference_id',$income->id)
                        ->where('transaction_type','profit_from_asset')
                        ->delete();
                    $income->delete();
                }
            }else{
                if ($assetSell->sale_price > $assetSell->purchase_price){
                    $income = Income::create([
                        'income_category_id' => 5,
                        'amount'=> $assetSell->sale_price - $assetSell->purchase_price,
                        'trx_id' => $assetSell->trx_id,
                        'date' => $assetSell->date,
                        'user_id' => Auth::id()
                    ]);

                    $creditTransaction = Transaction::create([
                        'account_name' => 'সম্পদ হতে প্রফিট',
                        'amount' => $income->amount,
                        'reference_id' => $income->id,
                        'transaction_type' => 'profit_from_asset',
                        'type' => 'credit',
                        'trx_id' => $assetSell->trx_id,
                        'user_id' => Auth::id(),
                        'date' => $assetSell->date
                    ]);

                }
            }

            $expense = Expense::where('trx_id', $assetSell->trx_id)->first();
            if ($expense){
                if ($assetSell->purchase_price > $assetSell->sale_price){
                    $expense->update([
                        'amount'=> $assetSell->purchase_price - $assetSell->sale_price,
                        'date' => $assetSell->date,
                    ]);

                    $debitTransacion = Transaction::where('reference_id', $expense->id)
                        ->where('transaction_type','loss_at_asset')
                        ->update([
                            'amount' => $expense->amount,
                            'date' => $expense->date
                        ]);
                }else{
                    Transaction::where('reference_id', $expense->id)
                        ->where('transaction_type','loss_at_asset')->delete();
                    $expense->delete();
                }
            }else{
                if ($assetSell->purchase_price > $assetSell->sale_price){
                    $expense = Expense::create([
                        'expense_category_id' => 22,
                        'amount'=> $assetSell->purchase_price - $assetSell->sale_price,
                        'trx_id' => $assetSell->trx_id,
                        'date' => $assetSell->date,
                        'user_id' => Auth::id()
                    ]);
                    $debitTransaction = Transaction::create([
                        'account_name' => 'সম্পদ হতে ক্ষতি',
                        'amount' => $expense->amount,
                        'reference_id' => $expense->id,
                        'transaction_type' => 'loss_at_asset',
                        'type' => 'debit',
                        'trx_id' => $assetSell->trx_id,
                        'user_id' => Auth::id(),
                        'date' => $assetSell->date
                    ]);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error($e);
            return redirect()->back()->with('error', 'Failed to update AssetSell: ' . $e->getMessage());
        }

        return redirect()->route('asset_sells.index')->with('success', 'AssetSell updated successfully.');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $assetSell = AssetSell::find($id);

            // Delete associated expenses, incomes, and transactions
            Expense::where('trx_id', $assetSell->trx_id)->delete();
            Income::where('trx_id', $assetSell->trx_id)->delete();
            Transaction::where('trx_id', $assetSell->trx_id)->delete();

            // Now delete the AssetSell record
            $assetSell->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to delete AssetSell: ' . $e->getMessage());
        }

        return redirect()->route('asset_sells.index')->with('success', 'AssetSell deleted successfully');
    }
}
