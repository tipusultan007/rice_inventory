<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Employee;
use App\Models\Expense;
use App\Models\Salary;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Class SalaryController
 * @package App\Http\Controllers
 */
class SalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $salaries = Salary::paginate(10);
        $employees = Employee::where('status','active')->get();
        $accounts = Account::all();

        return view('salary.index', compact('salaries','employees','accounts'))
            ->with('i', (request()->input('page', 1) - 1) * $salaries->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $salary = new Salary();
        $employees = Employee::where('status','active')->get();
        $accounts = Account::all();
        return view('salary.create', compact('salary','employees','accounts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Start a database transaction
        DB::beginTransaction();

        try {
            request()->validate(Salary::$rules);

            $data = $request->all();
            $data['trx_id'] = Str::uuid();
            $salary = Salary::create($data);

            $expense = Expense::create([
                'expense_category_id' => 20,
                'description' => $salary->employee->name,
                'date' => $salary->date,
                'amount' => $salary->amount,
                'user_id' => Auth::id(),
                'trx_id' => $salary->trx_id
            ]);

            $account = Account::find($request->input('account_id'));
            Transaction::create([
                'account_id' => $request->input('account_id'),
                'account_name' => $account->name,
                'amount' => $expense->amount,
                'type' => 'credit',
                'reference_id' => $expense->id,
                'date' => $expense->date,
                'transaction_type' => 'salary',
                'user_id' => Auth::id(),
                'trx_id' => $expense->trx_id,
            ]);

            Transaction::create([
                'account_name' => $salary->employee->name,
                'amount' => $expense->amount,
                'type' => 'debit',
                'reference_id' => $expense->id,
                'date' => $expense->date,
                'transaction_type' => 'salary',
                'user_id' => Auth::id(),
                'trx_id' => $expense->trx_id,
            ]);

            // Commit the transaction
            DB::commit();

            return redirect()->route('salaries.index')
                ->with('success', 'Salary created successfully.');
        } catch (\Exception $e) {
            // If an exception occurs, rollback the transaction
            DB::rollback();

            // You can handle the exception as per your application's logic
            return redirect()->back()
                ->with('error', 'Error occurred while saving the salary: ' . $e->getMessage());
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
        $salary = Salary::find($id);

        return view('salary.show', compact('salary'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $salary = Salary::find($id);
        $employees = Employee::all();

        $creditTransaction = Transaction::where('trx_id', $salary->trx_id)
            ->where('transaction_type','salary')->first();
        $accounts = Account::all();

        return view('salary.edit', compact('salary','employees','creditTransaction','accounts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Salary $salary
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Salary $salary)
    {
        // Start a database transaction
        DB::beginTransaction();

        try {
            request()->validate(Salary::$rules);

            $salary->update($request->all());
            $expense = Expense::where('trx_id', $salary->trx_id)->first();

            $expense->update([
                'date' => $salary->date,
                'amount' => $salary->amount,
            ]);

            $salaryTransaction = Transaction::where('trx_id', $expense->trx_id)
                ->where('transaction_type', 'salary')
                ->where('type', 'credit')
                ->first();

            $salaryTransaction->update([
                'amount' => $expense->amount,
                'date' => $expense->date,
            ]);

            $expenseTransaction = Transaction::where('trx_id', $expense->trx_id)
                ->where('transaction_type', 'salary')
                ->where('type', 'debit')
                ->first();

            $expenseTransaction->update([
                'amount' => $expense->amount,
                'date' => $expense->date,
            ]);

            DB::commit();

            return redirect()->route('salaries.index')
                ->with('success', 'Salary updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()
                ->with('error', 'Error occurred while updating the salary: ' . $e->getMessage());
        }
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $salary = Salary::find($id);
            Expense::where('trx_id', $salary->trx_id)->delete();
            Transaction::where('trx_id', $salary->trx_id)->delete();
            $salary->delete();

            DB::commit();

            return redirect()->route('salaries.index')
                ->with('success', 'Salary deleted successfully');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()
                ->with('error', 'Error occurred while deleting the salary: ' . $e->getMessage());
        }
    }
}
