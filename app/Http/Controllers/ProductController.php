<?php

namespace App\Http\Controllers;

use App\DataTables\ProductsDataTable;
use App\Models\Product;
use App\Models\PurchaseDetail;
use App\Models\PurchaseReturnDetail;
use App\Models\SaleDetail;
use App\Models\SaleReturnDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class ProductController
 * @package App\Http\Controllers
 */
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $allProducts = Product::select(DB::raw('SUM(quantity) as total_quantity,SUM(quantity*price_rate) as total_price'))->first();
        $productQuantity = Product::get();
        $products = Product::paginate(50);

        return view('product.index', compact('products','allProducts','productQuantity'))
            ->with('i', (request()->input('page', 1) - 1) * $products->perPage());
    }

    public function dataProducts(Request $request)
    {
        $columns = array(
            0 =>'name',
            1 =>'quantity',
            2=> 'price_rate',
        );

        $totalData = Product::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $posts = Product::offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =  Product::where('name','LIKE',"%{$search}%")
                ->orWhere('price_rate', 'LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered = Product::where('name','LIKE',"%{$search}%")
                ->orWhere('price_rate', 'LIKE',"%{$search}%")
                ->count();
        }

        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $show =  route('products.show',$post->id);
                $edit =  route('products.edit',$post->id);

                $nestedData['id'] = $post->id;
                $nestedData['name'] = $post->name;
                $nestedData['quantity'] = $post->quantity??'-';
                $nestedData['price_rate'] = $post->price_rate??'-';
                $nestedData['stock_value'] = $post->quantity * $post->price_rate;

                $nestedData['options'] = '<div class="dropdown">
                                              <a href="#" class="btn btn-sm dropdown-toggle" data-bs-toggle="dropdown">Action</a>
                                              <div class="dropdown-menu ">
                                                <a class="dropdown-item" href="'.route('products.show',$post->id).'">দেখুন</a>
                                                <a class="dropdown-item" href="'.route('products.edit',$post->id).'">এডিট</a>
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
        $product = new Product();
        return view('product.create', compact('product'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Product::$rules);

        $product = Product::create($request->all());

        if ($product->type === '25') {
            $product->name .= " - ২৫ কেজি";
        }else{
            $product->name .= " - ৫০ কেজি";
        }
        $product->quantity = $product->initial_stock;
        $product->save();

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);

        $purchases = PurchaseDetail::with('product','purchase')
            ->where('product_id',$id)
            ->paginate(30);
        $sales = SaleDetail::with('product','sale')
            ->where('product_id',$id)
            ->paginate(30);

        $purchaseReturns = PurchaseReturnDetail::with('product','purchaseReturn')
            ->where('product_id',$id)
            ->paginate(30);

        $saleReturns = SaleReturnDetail::with('product','saleReturn')
            ->where('product_id',$id)
            ->paginate(30);

        return view('product.show', compact('product',
            'purchases',
            'sales',
        'saleReturns',
        'purchaseReturns'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::find($id);

        return view('product.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        request()->validate(Product::$rules);

        $oldStock = $product->initial_stock;
        $product->update($request->all());
        $newStock = $product->initial_stock;
        $stockDifference = $newStock - $oldStock;
        $product->quantity += $stockDifference;
        $product->save();

        if ($product->type === '25') {
            $product->name .= " - ২৫ কেজি";
        }else{
            $product->name .= " - ৫০ কেজি";
        }
        $product->save();

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $product = Product::find($id)->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully');
    }
}
