<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index()
    {
        return view('admin.sale.index');
    }

    public function api() 
    {

        $sales = Sale::leftJoin('members', 'sales.member_id', '=', 'members.id')
            ->leftJoin('users', 'sales.user_id', '=', 'users.id')
            ->select('sales.*', 'member_name', 'name')
            ->orderBy('id', 'desc')->get();

        return datatables()->of($sales)
            ->addIndexColumn()
            ->addColumn('created_at', function ($sales) {
                return tanggal_indonesia($sales->created_at, false);
            })
            ->addColumn('total_price', function ($sales) {
                return 'Rp. '. format_uang($sales->total_price);
            })
            ->addColumn('pay', function ($sales) {
                return 'Rp. '. format_uang($sales->pay);
            })
            ->addColumn('accepted', function ($sales) {
                return 'Rp. '. format_uang($sales->accepted);
            })
            ->editColumn('discont', function ($sales) {
                return $sales->discont . '%';
            })
            ->addColumn('action', function ($sales) {
                return '
                <div class="btn-group">
                    <button onclick="showDetail(`'. route('sale.show', $sales->id) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye"></i></button>
                </div>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        $sale = new Sale();
        $sale->member_id = null;
        $sale->total_item = 0;
        $sale->total_price = 0;
        $sale->discont = 0;
        $sale->pay = 0;
        $sale->accepted = 0;
        $sale->user_id = auth()->id();
        $sale->save();

        // return $sale;
        session(['id' => $sale->id]);
        return redirect()->route('transaction.index');
        
    }

    public function show($id)
    {
        $detail = SaleDetail::with('produk')->where('sale_id', $id)->get();

        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('product_code', function ($detail) {
                return '<span class="label label-success">'. $detail->produk->product_code .'</span>';
            })
            ->addColumn('product_name', function ($detail) {
                return $detail->produk->product_name;
            })
            ->addColumn('sell_price', function ($detail) {
                return 'Rp. '. format_uang($detail->sell_price);
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


    public function store(Request $request)
    {
        // return $request;
        $sale = Sale::findOrFail($request->sale_id);
        $sale->member_id = $request->member_id;
        $sale->total_item = $request->total_item;
        $sale->total_price = $request->total;
        $sale->discont = $request->discont;
        $sale->pay = $request->pay;
        $sale->accepted = $request->accepted;
        $sale->update();

        $detail = SaleDetail::where('sale_id', $sale->id)->get();
        foreach ($detail as $item) {
            $item->discont = $request->discont;
            $item->update();

            $product = Product::find($item->product_id);
            $product->stock -= $item->amount;
            $product->update();
        }

        return redirect()->route('transaction.end');
    }

    public function end()
    {
        $setting = Setting::first();

        return view('admin.sale.selesai', compact('setting'));
    }

    public function notaKecil()
    {
        $setting = Setting::first();
        $sale = Sale::find(session('id'));
        if (! $sale) {
            abort(404);
        }
        $detail = SaleDetail::with('produk')
            ->where('sale_id', session('id'))
            ->get();
        
        return view('admin.sale.nota_kecil', compact('setting', 'sale', 'detail'));
    }

    public function notaBesar()
    {
        $setting = Setting::first();
        $sale = Sale::find(session('id'));
        if (! $sale) {
            abort(404);
        }
        $detail = SaleDetail::with('produk')
            ->where('sale_id', session('id'))
            ->get();

        $pdf = Pdf::loadView('admin.sale.nota_besar', compact('setting', 'sale', 'detail'));
        $pdf->setPaper(0,0,609,440, 'potrait');
        return $pdf->stream('Transaction-'. date('Y-m-d-his') .'.pdf');
    }


}
