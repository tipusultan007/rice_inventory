<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * Class SupplierController
 * @package App\Http\Controllers
 */
class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('supplier.index');
        /*return view('supplier.index', compact('suppliers'))
            ->with('i', (request()->input('page', 1) - 1) * $suppliers->perPage());*/
    }

    public function dataSuppliers(Request $request)
    {
        $columns = array(
            0 =>'name',
            3 =>'remaining_due',
        );

        $totalData = Supplier::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $posts = Supplier::offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =  Supplier::where('name','LIKE',"%{$search}%")
                ->orWhere('address', 'LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered = Supplier::where('name','LIKE',"%{$search}%")
                ->orWhere('address', 'LIKE',"%{$search}%")
                ->count();
        }

        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $show =  route('suppliers.show',$post->id);
                $edit =  route('suppliers.edit',$post->id);

                $nestedData['id'] = $post->id;
                $nestedData['name'] = $post->name;
                $nestedData['phone'] = $post->phone??'-';
                $nestedData['address'] = $post->address??'-';
                $nestedData['due'] = $post->remainingDue;

                $nestedData['options'] = '<div class="dropdown">
                                              <a href="#" class="btn btn-sm dropdown-toggle" data-bs-toggle="dropdown">Action</a>
                                              <div class="dropdown-menu ">
                                                <a class="dropdown-item" href="'.route('suppliers.show',$post->id).'">দেখুন</a>
                                                <a class="dropdown-item" href="'.route('suppliers.edit',$post->id).'">এডিট</a>
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
        $supplier = new Supplier();
        return view('supplier.create', compact('supplier'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Supplier::$rules);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();

            // Save the image to the storage folder
            $image->storeAs('suppliers', $imageName, 'public');

            // Update the data array with the image path
            $data['image'] = 'suppliers/' . $imageName;
        }

        $supplier = Supplier::create($data);
        if ($supplier->starting_balance > 0){
           $creditTransaction = Transaction::create([
                'account_name' => $supplier->name,
                'amount' => $supplier->starting_balance,
                'transaction_type' => 'supplier_opening_balance',
                'type' => 'credit',
                'reference_id' => $supplier->id,
                'supplier_id' => $supplier->id,
                'user_id' => Auth::id(),
                'date' => $request->date,
            ]);
        }

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $supplier = Supplier::find($id);
        $lastTrx = Transaction::where('user_id',Auth::id())->latest()->first();

        $transactions = Transaction::where('supplier_id', $supplier->id)
            /*->whereIn('transaction_type',['purchase','supplier_payment','purchase_return','supplier_opening_balance'])*/
            ->where(function($query) {
                $query->where(function($query) {
                    $query->where('transaction_type', 'supplier_payment')
                        ->where('type', 'credit');
                })->orWhere(function($query) {
                    $query->where('transaction_type', 'purchase')
                        ->where('type', 'debit');
                })->orWhere(function($query) {
                    $query->where('transaction_type', 'supplier_opening_balance')
                        ->where('type', 'credit');
                })->orWhere(function($query) {
                    $query->where('transaction_type', 'purchase_return')
                        ->where('type', 'credit');
                });
            })
            ->orderBy('id', 'asc')
            ->get();

        return view('supplier.show', compact('supplier','transactions','lastTrx'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $supplier = Supplier::find($id);

        return view('supplier.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Supplier $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Supplier $supplier)
    {
        request()->validate(Supplier::$rules);
        $data = $request->all();

        // Handling image update
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();

            // Save the new image to the storage folder
            $image->storeAs('suppliers', $imageName, 'public');

            // Delete the old image if it exists
            if ($supplier->image) {
                Storage::disk('public')->delete($supplier->image);
            }

            // Update the data array with the new image path
            $data['image'] = 'suppliers/' . $imageName;
        }
        $supplier->update($data);

        $creditTransaction = Transaction::where('transaction_type','supplier_opening_balance')
            ->where('reference_id', $supplier->id)->first();
        if ($creditTransaction){
            if ($supplier->starting_balance > 0) {
                $creditTransaction->amount = $supplier->starting_balance;
                $creditTransaction->date = $request->date;
                $creditTransaction->save();
            }else{
                $creditTransaction->delete();
            }
        }else {
            if ($supplier->starting_balance > 0){
                $creditTransaction = Transaction::create([
                    'account_name' => $supplier->name,
                    'amount' => $supplier->starting_balance,
                    'transaction_type' => 'supplier_opening_balance',
                    'type' => 'credit',
                    'reference_id' => $supplier->id,
                    'supplier_id' => $supplier->id,
                    'user_id' => Auth::id(),
                    'date' => $request->date,
                ]);
            }
        }

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $supplier = Supplier::find($id)->delete();
        return response()->json([
            'status' => 'success'
        ]);
    }
}
