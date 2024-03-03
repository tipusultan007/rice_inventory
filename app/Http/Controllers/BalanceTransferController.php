<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\BalanceTransfer;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Class BalanceTransferController
 * @package App\Http\Controllers
 */
class BalanceTransferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $balanceTransfers = BalanceTransfer::with('fromAccount','toAccount')
            ->orderBy('id','desc')
            ->paginate(20);

        return view('balance-transfer.index', compact('balanceTransfers'))
            ->with('i', (request()->input('page', 1) - 1) * $balanceTransfers->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $balanceTransfer = new BalanceTransfer();
        return view('balance-transfer.create', compact('balanceTransfer'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'from_account_id' => 'required|exists:accounts,id',
            'to_account_id' => 'required|exists:accounts,id',
            'amount' => 'required|numeric|min:0',
            'date' => 'required',
        ]);
        // Begin a database transaction
        DB::beginTransaction();
        // Create a balance transfer
        try {

            $balanceTransfer = BalanceTransfer::create([
                'from_account_id' => $request->input('from_account_id'),
                'to_account_id' => $request->input('to_account_id'),
                'amount' => $request->input('amount'),
                'date' => $request->input('date'),
            ]);

            // Create a transaction for the balance transfer from the source account
            Transaction::create([
                'account_id' => $request->input('from_account_id'),
                'amount' => $request->input('amount'),
                'type' => 'debit',
                'reference_id' => $balanceTransfer->id,
                'date' => $balanceTransfer->date,
                'transaction_type' => 'balance_transfer_out',
            ]);

            // Create a transaction for the balance transfer to the destination account
            Transaction::create([
                'account_id' => $request->input('to_account_id'),
                'amount' => $request->input('amount'),
                'type' => 'credit',
                'reference_id' => $balanceTransfer->id,
                'date' => $balanceTransfer->date,
                'transaction_type' => 'balance_transfer_in',
            ]);


        } catch (\Exception $e) {
            // An error occurred, rollback the transaction
            DB::rollback();

            return redirect()->route('balance_transfers.create')
                ->with('error', 'Balance transfer creation failed.');
        }
// Commit the transaction
        DB::commit();
        return redirect()->route('balance_transfers.index')
            ->with('success', 'Balance transfer created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $balanceTransfer = BalanceTransfer::find($id);

        return view('balance-transfer.show', compact('balanceTransfer'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $balanceTransfer = BalanceTransfer::find($id);

        return view('balance-transfer.edit', compact('balanceTransfer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  BalanceTransfer $balanceTransfer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BalanceTransfer $balanceTransfer)
    {
        // Validate the request
        $request->validate([
            'from_account_id' => 'required|exists:accounts,id',
            'to_account_id' => 'required|exists:accounts,id',
            'amount' => 'required|numeric|min:0',
            'date' => 'required',
        ]);

        // Begin a database transaction
        DB::beginTransaction();

        try {
            // Update the balance transfer
            $balanceTransfer->update([
                'from_account_id' => $request->input('from_account_id'),
                'to_account_id' => $request->input('to_account_id'),
                'amount' => $request->input('amount'),
                'date' => $request->input('date'),
            ]);

            // Update the transaction for the balance transfer from the source account
            $debitTransaction = Transaction::where('reference_id', $balanceTransfer->id)
                ->where('transaction_type', 'balance_transfer')
                ->where('type', 'debit')
                ->first();

            if ($debitTransaction) {
                $debitTransaction->update([
                    'account_id' => $request->input('from_account_id'),
                    'amount' => $request->input('amount'),
                    'date' => $request->input('date'),
                ]);
            }

            // Update the transaction for the balance transfer to the destination account
            $creditTransaction = Transaction::where('reference_id', $balanceTransfer->id)
                ->where('transaction_type', 'balance_transfer')
                ->where('type', 'credit')
                ->first();

            if ($creditTransaction) {
                $creditTransaction->update([
                    'account_id' => $request->input('to_account_id'),
                    'amount' => $request->input('amount'),
                    'date' => $request->input('date'),
                ]);
            }

            // Commit the transaction
            DB::commit();

            return redirect()->route('balance_transfers.index')
                ->with('success', 'Balance transfer updated successfully');
        } catch (\Exception $e) {
            // An error occurred, rollback the transaction
            DB::rollback();

            // Log the error or handle it in a way that fits your application
            // You might want to log the error, display a user-friendly message, or redirect to an error page
            throw ValidationException::withMessages(['error' => 'Balance transfer update failed.']);
        }
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(BalanceTransfer $balanceTransfer)
    {
        // Begin a database transaction
        DB::beginTransaction();

        try {
            // Retrieve related transactions
            $debitTransaction = Transaction::where('reference_id', $balanceTransfer->id)
                ->where('transaction_type', 'balance_transfer')
                ->where('type', 'debit')
                ->first();

            $creditTransaction = Transaction::where('reference_id', $balanceTransfer->id)
                ->where('transaction_type', 'balance_transfer')
                ->where('type', 'credit')
                ->first();

            // Delete related transactions
            if ($debitTransaction) {
                $debitTransaction->delete();
            }

            if ($creditTransaction) {
                $creditTransaction->delete();
            }


            // Delete the balance transfer
            $balanceTransfer->delete();

            // Commit the transaction
            DB::commit();

            return redirect()->route('balance_transfers.index')
                ->with('success', 'Balance transfer deleted successfully');
        } catch (\Exception $e) {
            // An error occurred, rollback the transaction
            DB::rollback();

            // Log the error or handle it in a way that fits your application
            // You might want to log the error, display a user-friendly message, or redirect to an error page
            throw ValidationException::withMessages(['error' => 'Balance transfer deletion failed.']);
        }
    }
}
