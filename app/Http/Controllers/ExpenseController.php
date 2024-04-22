<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Class ExpenseController
 * @package App\Http\Controllers
 */
class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $expenses = Expense::orderByDesc('date')->paginate(10);

        $categories = ExpenseCategory::all();

        return view('expense.index', compact('expenses','categories'))
            ->with('i', (request()->input('page', 1) - 1) * $expenses->perPage());
    }

    public function dataExpenses(Request $request)
    {
        $columns = array(
            0 =>'date',
            1=> 'expense_category_id',
        );

        $totalData = Expense::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $posts = Expense::with('category')->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =  Expense::with('category')
                ->whereHas('category',function ($query) use ($search){
                    $query->where('name', 'LIKE',"%{$search}%");
                })->orWhere('description', 'LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered = Expense::whereHas('category',function ($query) use ($search){
                $query->where('name', 'LIKE',"%{$search}%");
            })->orWhere('description', 'LIKE',"%{$search}%")
                ->count();
        }

        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData['id'] = $post->id;
                $nestedData['category'] = $post->category->name;
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $expense = new Expense();
        return view('expense.create', compact('expense'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Expense::$rules);

        $data = $request->all();
        $data['trx_id'] = Str::uuid();
        $data['user_id'] = Auth::id();
        $expense = Expense::create($data);
        $account = Account::find($request->input('account_id'));
        // Create a transaction for the balance transfer from the source account
        Transaction::create([
            'account_id' => $request->input('account_id'),
            'account_name' => $account->name,
            'amount' => $request->input('amount'),
            'type' => 'credit',
            'reference_id' => $expense->id,
            'date' => $expense->date,
            'transaction_type' => 'expense',
            'user_id' => Auth::id(),
            'trx_id' => $expense->trx_id
        ]);

        // Create a transaction for the balance transfer to the destination account
        Transaction::create([
            'account_name' => $expense->category->name,
            'amount' => $request->input('amount'),
            'type' => 'debit',
            'reference_id' => $expense->id,
            'date' => $expense->date,
            'transaction_type' => 'expense',
            'user_id' => Auth::id(),
            'trx_id' => $expense->trx_id
        ]);

        return redirect()->route('expenses.index')
            ->with('success', 'Expense created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $expense = Expense::find($id);

        return view('expense.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $expense = Expense::find($id);

        $transaction = Transaction::where('trx_id', $expense->trx_id)
            ->where('type','credit')
            ->first();

        return view('expense.edit', compact('expense','transaction'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Expense $expense
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Expense $expense)
    {

        $expense->update($request->all());

        $account = Account::find($request->input('account_id'));
        $creditTransaction = Transaction::where('trx_id', $expense->trx_id)
            ->where('type','credit')->first();
        $account = Account::find($request->input('account_id'));
        if ($creditTransaction)
        {
            $creditTransaction->update([
                'account_id' => $request->input('account_id'),
                'account_name' => $account->name,
                'amount' => $request->input('amount'),
                'date' => $expense->date,
            ]);
        }
        $debitTransaction = Transaction::where('trx_id', $expense->trx_id)
            ->where('type','debit')->first();
        if ($debitTransaction) {
            $debitTransaction->update([
                'account_name' => $expense->category->name,
                'amount' => $request->input('amount'),
                'date' => $expense->date,
            ]);
        }

        return redirect()->route('expenses.index')
            ->with('success', 'Expense updated successfully.');
    }


    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $expense = Expense::find($id);

        Transaction::where('transaction_type','expense')
            ->where('trx_id',$expense->trx_id)
            ->delete();

        $expense->delete();

        return response()->json(['success','Successfully deleted!'],200);
    }
}
