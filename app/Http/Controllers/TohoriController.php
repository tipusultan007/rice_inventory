<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Tohori;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Class TohoriController
 * @package App\Http\Controllers
 */
class TohoriController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tohoriWithdraws = Tohori::orderBy('date','desc')->paginate(10);
        $tohoris = Purchase::where('tohori','>',0)->orderBy('date','desc')->paginate(10);

        return view('tohori.index', compact('tohoriWithdraws','tohoris'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tohori = new Tohori();
        return view('tohori.create', compact('tohori'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            request()->validate(Tohori::$rules);

            $data = $request->all();
            $data['trx_id'] = Str::uuid();
            $data['user_id'] = Auth::id();
            $tohori = Tohori::create($data);

            $debitTransaction1 = Transaction::create([
                'account_id' => 13,
                'account_name' => 'তহরি তহবিল',
                'amount' => $request->input('amount'),
                'type' => 'debit',
                'transaction_type' => 'tohori_fund',
                'reference_id' => $tohori->id,
                'trx_id' => $tohori->trx_id,
                'date' => $tohori->date,
                'user_id' => Auth::id(),
            ]);

            $creditTransaction1 = Transaction::create([
                'account_name' => 'নগদ',
                'account_id' => 1,
                'amount' => $tohori->amount,
                'type' => 'credit',
                'reference_id' => $tohori->id,
                'transaction_type' => 'tohori',
                'date' => $tohori->date,
                'user_id' => $tohori->user_id,
                'trx_id' => $tohori->trx_id,
            ]);

            DB::commit();

            return redirect()->route('tohoris.index')
                ->with('success', 'Tohori created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred. Transaction rolled back.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tohori = Tohori::find($id);

        return view('tohori.show', compact('tohori'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tohori = Tohori::find($id);

        return view('tohori.edit', compact('tohori'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Tohori $tohori
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tohori $tohori)
    {
        DB::beginTransaction();

        try {
            // Validate the request data
            $request->validate(Tohori::$rules);

            // Update the Tohori record
            $tohori->update($request->all());

            // Update the related debit transaction
            $debitTransaction = Transaction::where('reference_id', $tohori->id)
                ->where('transaction_type', 'tohori_fund')
                ->where('type', 'debit')
                ->update([
                    'amount' => $request->input('amount'),
                    'date' => $tohori->date,
                    'user_id' => Auth::id(),
                ]);

            // Update the related credit transaction
            $creditTransaction = Transaction::where('reference_id', $tohori->id)
                ->where('transaction_type', 'tohori')
                ->where('type', 'credit')
                ->update([
                    'amount' => $request->input('amount'),
                    'date' => $tohori->date,
                    'user_id' => $tohori->user_id,
                ]);

            DB::commit();

            return redirect()->route('tohoris.index')
                ->with('success', 'Tohori updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred. Transaction rolled back.');
        }
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $tohori = Tohori::find($id);
        Transaction::where('trx_id', $tohori->trx_id)->delete();
        $tohori->delete();

        return redirect()->route('tohoris.index')
            ->with('success', 'Tohori deleted successfully');
    }
}
