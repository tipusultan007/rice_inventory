<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Payment;
use App\Models\Sale;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * Class CustomerController
 * @package App\Http\Controllers
 */
class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*$customers = Customer::all();
        foreach ($customers as $customer) {
            Transaction::create([
                'account_name' => $customer->name,
                'amount' => $customer->starting_balance,
                'transaction_type' => 'customer_opening_balance',
                'type' => 'debit',
                'reference_id' => $customer->id,
                'customer_id' => $customer->id,
                'user_id' => Auth::id(),
                'date' => '2024-04-09',
            ]);
        }*/
        return view('customer.index');
    }

    public function dataCustomers(Request $request)
    {
            $columns = array(
                0 =>'name',
            );

            $totalData = Customer::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');

            if(empty($request->input('search.value')))
            {
                $posts = Customer::offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();
            }
            else {
                $search = $request->input('search.value');

                $posts =  Customer::where('name','LIKE',"%{$search}%")
                    ->orWhere('address', 'LIKE',"%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();

                $totalFiltered = Customer::where('name','LIKE',"%{$search}%")
                    ->orWhere('address', 'LIKE',"%{$search}%")
                    ->count();
            }

            $data = array();
            if(!empty($posts))
            {
                foreach ($posts as $post)
                {
                    $show =  route('customers.show',$post->id);
                    $edit =  route('customers.edit',$post->id);

                    $nestedData['id'] = $post->id;
                    $nestedData['name'] = $post->name;
                    $nestedData['phone'] = $post->phone??'-';
                    $nestedData['address'] = $post->address??'-';
                    $nestedData['due'] = $post->remaining_due??'0';

                    $nestedData['options'] = '<div class="dropdown">
                                              <a href="#" class="btn btn-sm dropdown-toggle" data-bs-toggle="dropdown">Action</a>
                                              <div class="dropdown-menu ">
                                                <a class="dropdown-item" href="'.route('customers.show',$post->id).'">দেখুন</a>
                                                <a class="dropdown-item" href="'.route('customers.edit',$post->id).'">এডিট</a>
                                                <a class="dropdown-item text-danger delete" href="javascript:;" data-id="'.$post->id.'">ডিলেট</a>
                                              </div>
                                            </div>
                                            ';
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
        $customer = new Customer();
        return view('customer.create', compact('customer'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Customer::$rules);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();

            // Save the image to the storage folder
            $image->storeAs('customers', $imageName, 'public');

            // Update the data array with the image path
            $data['image'] = 'customers/' . $imageName;
        }

        $customer = Customer::create($data);

        if ($customer->starting_balance > 0){
            Transaction::create([
                'account_name' => $customer->name,
                'amount' => $customer->starting_balance,
                'transaction_type' => 'customer_opening_balance',
                'type' => 'debit',
                'reference_id' => $customer->id,
                'customer_id' => $customer->id,
                'user_id' => Auth::id(),
                'date' => $request->date,
            ]);
        }

        return redirect()->route('customers.index')
            ->with('success', 'Customer created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customer = Customer::find($id);
        $lastTrx = Transaction::where('user_id',Auth::id())->latest()->first();

        $transactions = Transaction::where('customer_id', $customer->id)
            /*->whereIn('transaction_type',['sale','customer_payment','sale_return','customer_opening_balance'])*/
            ->where(function($query) {
                $query->where(function($query) {
                    $query->where('transaction_type', 'customer_payment')
                        ->where('type', 'debit');
                })->orWhere(function($query) {
                    $query->where('transaction_type', 'sale')
                        ->where('type', 'credit');
                })->orWhere(function($query) {
                    $query->where('transaction_type', 'customer_opening_balance')
                        ->where('type', 'debit');
                })->orWhere(function($query) {
                    $query->where('transaction_type', 'sale_return')
                        ->where('type', 'debit');
                });
            })
            ->orderBy('id', 'asc')
            ->get();

        return view('customer.show', compact('customer','transactions','lastTrx'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customer = Customer::find($id);

        return view('customer.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Customer $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        request()->validate(Customer::$rules);

        $data = $request->all();

        // Handling image update
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();

            // Save the new image to the storage folder
            $image->storeAs('customers', $imageName, 'public');

            // Delete the old image if it exists
            if ($customer->image) {
                Storage::disk('public')->delete($customer->image);
            }

            // Update the data array with the new image path
            $data['image'] = 'customers/' . $imageName;
        }
        $customer->update($data);

        $debitTransaction = Transaction::where('transaction_type','customer_opening_balance')
            ->where('reference_id', $customer->id)->first();
        if ($debitTransaction){
            if ($customer->starting_balance > 0) {
                $debitTransaction->amount = $customer->starting_balance;
                $debitTransaction->date = $request->date;
                $debitTransaction->save();
            }else{
                $debitTransaction->delete();
            }
        }else {
            if ($customer->starting_balance > 0) {
                Transaction::create([
                    'account_name' => $customer->name,
                    'amount' => $customer->starting_balance,
                    'transaction_type' => 'customer_opening_balance',
                    'type' => 'debit',
                    'reference_id' => $customer->id,
                    'customer_id' => $customer->id,
                    'user_id' => Auth::id(),
                    'date' => $request->date,
                ]);
            }
        }

        return redirect()->route('customers.index')
            ->with('success', 'Customer updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $customer = Customer::find($id)->delete();
        return response()->json([
           'status' => 'success'
        ]);
    }
}
