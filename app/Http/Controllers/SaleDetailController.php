<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Setting;
use Illuminate\Http\Request;

class SaleDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $product = Product::orderBy('product_name')->get();
        $member = Member::orderBy('member_name')->get();
        $discont = Setting::first()->discont ?? 0;

        if ($sale_id = session('id')) {
            $sale = Sale::find($sale_id);
            $memberSelected = $sale->member ?? new Member();
            return view('admin.sale_detail.index', compact('product', 'discont', 'member', 'sale_id', 'sale', 'memberSelected'));
        } else {
            if (auth()->user()->level == 1) {
                return redirect()->route('transaction.new');
            } else {
                return redirect()->route('home');
            }
        }
        
    }

    public function data($id)
    {
        $detail = SaleDetail::with('produk')
            ->where('sale_id', $id)
            ->get();
        
        $data = array();
        $total = 0;
        $total_item = 0;

        foreach ($detail as $item) {
            $row = array();
            $row['product_code'] = '<span class="label label-success">'. $item->produk['product_code'] .'</span';
            $row['product_name'] = $item->produk['product_name'];
            $row['sell_price']  = 'Rp. '. format_uang($item->sell_price);
            $row['amount']      = '<input type="number" class="form-control input-sm quantity" data-id="'. $item->id .'" value="'. $item->amount .'">';
            $row['discont']      = $item->discont . '%';
            $row['subtotal']    = 'Rp. '. format_uang($item->subtotal);
            $row['aksi']        = '<div class="btn-group">
                                    <button onclick="deleteData(`'. route('transaction.destroy', $item->id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                                </div>';
            $data[] = $row;

            $total += $item->sell_price * $item->amount;
            $total_item += $item->amount;
        }
        $data[] = [
            'product_code' => '
                <div class="total hide">'. $total .'</div>
                <div class="total_item hide">'. $total_item .'</div>',
            'product_name' => '',
            'sell_price'  => '',
            'amount'      => '',
            'discont'      => '',
            'subtotal'    => '',
            'aksi'        => '',
        ];

        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['aksi', 'product_code', 'amount'])
            ->make(true);
    }

    public function store(Request $request)
    {
     
        $product = Product::where('id', $request->id)->first();
        if (! $product) {
            return response()->json('Data gagal disimpan', 400);
        }

        $detail = new SaleDetail();
        $detail->sale_id = $request->sale_id;
        $detail->product_id = $product->id; // Sesuaikan dengan nama kolom yang benar
        $detail->sell_price = $product->sell_price;
        $detail->amount = 1;
        $detail->discont = 0;
        $detail->subtotal = $product->sell_price;
        $detail->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    public function update(Request $request, $id)
    {
        $detail = SaleDetail::find($id);
        $detail->amount = $request->amount;
        $detail->subtotal = $detail->sell_price * $request->amount;
        $detail->update();
    }

    public function loadForm($discont = 0, $total = 0, $accepted = 0)
    {
        $pay   = $total - ($discont / 100 * $total);
        $accepted = ($accepted != 0) ? $accepted - $pay : 0;
        $data    = [
            'totalrp' => format_uang($total),
            'pay' => $pay,
            'bayarrp' => format_uang($pay),
            'terbilang' => ucwords(terbilang($pay). ' Rupiah'),
            'kembalirp' => format_uang($accepted),
            'kembali_terbilang' => ucwords(terbilang($accepted). ' Rupiah'),
        ];

        return response()->json($data);
    }

    public function destroy($id)
    {
        $detail = SaleDetail::find($id);

        if ($detail) {
            $detail->delete();
            return response(null, 204);
        } else {
            // Handle jika tidak menemukan catatan
            return response('Purchase detail not found', 404);
        }
        
    }



}
