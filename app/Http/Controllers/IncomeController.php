<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Income;
use App\Models\IncomeCategory;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Class IncomeController
 * @package App\Http\Controllers
 */
class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $incomes = Income::orderByDesc('date')->paginate(10);
        $categories = IncomeCategory::all();

        return view('income.index', compact('incomes','categories'))
            ->with('i', (request()->input('page', 1) - 1) * $incomes->perPage());
    }
    public function dataIncomes(Request $request)
    {
        $columns = array(
            0 =>'date',
            1=> 'income_category_id',
        );

        $totalData = Income::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $posts = Income::with('category')->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =  Income::with('category')
                ->whereHas('category',function ($query) use ($search){
                    $query->where('name', 'LIKE',"%{$search}%");
                })->orWhere('description', 'LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered = Income::whereHas('category',function ($query) use ($search){
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
        $income = new Income();
        return view('income.create', compact('income'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Income::$rules);

        $data = $request->all();
        $data['trx_id'] = Str::uuid();
        $data['user_id'] = Auth::id();

        $income = Income::create($data);

        $account = Account::find($request->input('account_id'));
        Transaction::create([
            'account_id' => $request->input('account_id'),
            'account_name' => $account->name,
            'amount' => $request->input('amount'),
            'type' => 'debit',
            'reference_id' => $income->id,
            'date' => $income->date,
            'transaction_type' => 'income',
            'user_id' => Auth::id(),
            'trx_id' => $income->trx_id
        ]);

        // Create a transaction for the balance transfer to the destination account
        Transaction::create([
            'account_name' => $income->category->name,
            'amount' => $request->input('amount'),
            'type' => 'credit',
            'reference_id' => $income->id,
            'date' => $income->date,
            'transaction_type' => 'income',
            'user_id' => Auth::id(),
            'trx_id' => $income->trx_id
        ]);

        return redirect()->route('incomes.index')
            ->with('success', 'Income created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $income = Income::find($id);

        return view('income.show', compact('income'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $income = Income::find($id);
        $transaction = Transaction::where('trx_id', $income->trx_id)
            ->where('transaction_type','income')->first();

        return view('income.edit', compact('income','transaction'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Income $income
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Income $income)
    {
        request()->validate(Income::$rules);

        $income->update($request->all());


        $account = Account::find($request->input('account_id'));
        $debitTransaction = Transaction::where('trx_id', $income->trx_id)
            ->where('type','debit')->first();
        if ($debitTransaction){
            $debitTransaction->update([
                'account_id' => $request->input('account_id'),
                'account_name' => $account->name,
                'amount' => $request->input('amount'),
                'date' => $income->date,
            ]);
        }

        $creditTransaction = Transaction::where('trx_id', $income->trx_id)
            ->where('type','credit')->first();
        if ($creditTransaction) {
            $creditTransaction->update([
                'account_name' => $income->category->name,
                'amount' => $request->input('amount'),
                'date' => $income->date,
            ]);
        }

        return redirect()->route('incomes.index')
            ->with('success', 'Income updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $income = Income::find($id);

        Transaction::where('trx_id',$income->trx_id)->delete();
        $income->delete();

        return response()->json(['success','Successfully deleted!'],200);
    }
}
