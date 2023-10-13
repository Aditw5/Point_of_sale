<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
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
        $suppliers = Supplier::all();

        return view('admin.supplier.supplier', compact('suppliers'));
    }

    public function api() 
    {
        $suppliers = Supplier::all();
        $datatables = datatables()->of($suppliers)
            ->addIndexColumn()
            ->addColumn('created_at', function ($suppliers) {
                return tanggal_indonesia($suppliers->created_at, false);
            });

        return $datatables->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'supplier_name' => ['required'],
            'address' => ['required'],
            'phone' => ['required'],
        ]);

        Supplier::create($request->all());

        return redirect('suppliers');
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $this->validate($request, [
            'supplier_name' => ['required'],
            'address' => ['required'],
            'phone' => ['required'],
        ]);

        $supplier->update($request->all());

        return redirect('suppliers');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
    }
}
