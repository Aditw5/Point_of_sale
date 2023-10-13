<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $categorys = Category::all();

        return view('admin.product.product', compact('categorys'));
    }

    public function api() 
    {
        $products = Product::leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.*', 'name')
            ->orderBy('id')->get();

        return datatables()->of($products)
            ->addIndexColumn()
            ->addColumn('sell_price', function ($products) {
                return format_uang($products->sell_price);
            })
            ->addColumn('buy_price', function ($products) {
                return format_uang($products->buy_price);
            })
            ->addColumn('select_all', function ($products) {
                return '<input type="checkbox" name="id[]" value="'. $products->id .'">';
            })
            ->rawColumns(['select_all'])
            ->make(true);
    }

    public function deleteSelected(Request $request)
    {
        foreach ($request->id as $product_id) {
            $product = Product::find($product_id);
            $product->delete();
        }

        return response(null, 204);
    }

    public function PrintBarcode(Request $request)
    {
        $dataproduk = array();
        foreach ($request->id as $product_id) {
            $product = Product::find($product_id);
            $dataproduk[] = $product;
        }
    
        $no  = 1;
        $pdf = Pdf::loadView('admin.product.barcode', compact('dataproduk', 'no'));
        $pdf->setPaper('a4', 'potrait');
        return $pdf->stream('produk.pdf');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $product = Product::latest()->first();
        $request['product_code'] = 'P'. add_nol((int)$product->id+1, 5);
        $this->validate($request, [
            'category_id' => ['required'],
            'product_code' => ['required'],
            'product_name' => ['required'],
            'merk' => ['required'],
            'buy_price' => ['required'],
            'sell_price' => ['required'],
            'stock' => ['required'],
            'discon' => ['required'],
        ]);

        $product = Product::create($request->all());

        return redirect('categorys');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $this->validate($request, [
            'category_id' => ['required'],
            'product_name' => ['required'],
            'merk' => ['required'],
            'buy_price' => ['required'],
            'sell_price' => ['required'],
            'stock' => ['required'],
            'discon' => ['required'],
        ]);

        $product->update($request->all());

        return redirect('categorys');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
    }
}
