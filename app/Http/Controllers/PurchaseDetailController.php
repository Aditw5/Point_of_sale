<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PurchaseDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchase_id = session('id');
        $product = Product::orderBy('product_name')->get();
        $supplier = Supplier::find(session('supplier_id'));
        $discont = Purchase::find($purchase_id)->discont ?? 0;

        // return session('id');
  

        return view('admin.purchase_detail.index', compact('purchase_id', 'product', 'supplier', 'discont'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function data($id)
    {
        $detail = PurchaseDetail::with('produk')
            ->where('purchase_id', $id)
            ->get();
        
            
        $data = array();
        $total = 0;
        $total_item = 0;

        foreach ($detail as $item) {
            $row = array();
            $row['product_code'] = '<span class="label label-success">'. $item->produk['product_code'] .'</span';
            $row['product_name'] = $item->produk['product_name'];
            $row['buy_price']  = 'Rp. '. format_uang($item->buy_price);
            $row['amount']      = '<input type="number" class="form-control input-sm quantity" data-id="'. $item->id .'" value="'. $item->amount .'">';
            $row['subtotal']    = 'Rp. '. format_uang($item->subtotal);
            $row['aksi']        = '<div class="btn-group">
                                    <button onclick="deleteData(`'. route('purchases_detail.destroy', $item->id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                                </div>';
            $data[] = $row;

            $total += $item->buy_price * $item->amount;
            $total_item += $item->amount;
        }
        $data[] = [
            'product_code' => '
                <div class="total hide">'. $total .'</div>
                <div class="total_item hide">'. $total_item .'</div>',
            'product_name' => '',
            'buy_price'  => '',
            'amount'      => '',
            'subtotal'    => '',
            'aksi'        => '',
        ];

        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['aksi', 'product_code', 'amount'])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
     
        $product = Product::where('id', $request->id)->first();
        if (! $product) {
            return response()->json('Data gagal disimpan', 400);
        }

        $detail = new PurchaseDetail();
        $detail->purchase_id = $request->purchase_id;
        $detail->product_id = $product->id; // Sesuaikan dengan nama kolom yang benar
        $detail->buy_price = $product->buy_price;
        $detail->amount = 1;
        $detail->subtotal = $product->buy_price;
        $detail->save();

        return response()->json('Data berhasil disimpan', 200);
    }


    /**
     * Display the specified resource.
     */
    public function show(PurchaseDetail $purchaseDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PurchaseDetail $purchaseDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $detail = PurchaseDetail::find($id);
        $detail->amount = $request->amount;
        $detail->subtotal = $detail->buy_price * $request->amount;
        $detail->update();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $detail = PurchaseDetail::find($id);

        if ($detail) {
            $detail->delete();
            return response(null, 204);
        } else {
            // Handle jika tidak menemukan catatan
            return response('Purchase detail not found', 404);
        }
        
    }

    public function loadForm($diskon, $total)
    {
        $bayar = $total - ($diskon / 100 * $total);
        $data  = [
            'totalrp' => format_uang($total),
            'bayar' => $bayar,
            'bayarrp' => format_uang($bayar),
            'terbilang' => ucwords(terbilang($bayar). ' Rupiah')
        ];

        return response()->json($data);
    }
}
