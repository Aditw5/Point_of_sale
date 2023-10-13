<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PurchaseController extends Controller
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
        $suppliers = Supplier::orderBy('supplier_name')->get();

        return view('admin.purchase.purchase', compact('suppliers'));
    }

    public function api() 
    {

        $purchases = Purchase::leftJoin('suppliers', 'purchases.supplier_id', '=', 'suppliers.id')
            ->select('purchases.*', 'supplier_name')
            ->orderBy('id', 'desc')->get();

        return datatables()->of($purchases)
            ->addIndexColumn()
            ->addColumn('created_at', function ($categorys) {
                return tanggal_indonesia($categorys->created_at, false);
            })
            ->addColumn('total_price', function ($purchases) {
                return 'Rp. '. format_uang($purchases->total_price);
            })
            ->addColumn('pay', function ($purchases) {
                return 'Rp. '. format_uang($purchases->pay);
            })
            ->editColumn('discont', function ($purchases) {
                return $purchases->discont . '%';
            })
            ->addColumn('action', function ($purchases) {
                return '
                <div class="btn-group">
                    <button onclick="showDetail(`'. route('purchases.show', $purchases->id) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye"></i></button>
                </div>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */



    public function create($id)
    {
        $purchase = new Purchase();
        $purchase->supplier_id = $id;
        $purchase->total_item  = 0;
        $purchase->total_price= 0;
        $purchase->discont     = 0;
        $purchase->pay       = 0;
        $purchase->save();

        session(['id' => $purchase->id]);
        session(['supplier_id' => $purchase->supplier_id]);

        return redirect()->route('purchases_detail.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $purchase = Purchase::findOrFail($request->purchase_id);
        $purchase->total_item = $request->total_item;
        $purchase->total_price = $request->total;
        $purchase->discont = $request->discont;
        $purchase->pay = $request->pay;
        $purchase->update();

        $details = PurchaseDetail::where('purchase_id', $purchase->id)->get();
    
        foreach ($details as $detail) {
            $product = Product::find($detail->product_id);
            $product->stock += $detail->amount;
            $product->update();
        }
    
        return redirect()->route('purchases.index');
    }
    

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $detail = PurchaseDetail::with('produk')->where('purchase_id', $id)->get();

        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('product_code', function ($detail) {
                return '<span class="label label-success">'. $detail->produk->product_code .'</span>';
            })
            ->addColumn('product_name', function ($detail) {
                return $detail->produk->product_name;
            })
            ->addColumn('buy_price', function ($detail) {
                return 'Rp. '. format_uang($detail->buy_price);
            })
            ->addColumn('amount', function ($detail) {
                return format_uang($detail->amount);
            })
            ->addColumn('subtotal', function ($detail) {
                return 'Rp. '. format_uang($detail->subtotal);
            })
            ->rawColumns(['product_code'])
            ->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchase $purchase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Purchase $purchase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        //
    }
}
