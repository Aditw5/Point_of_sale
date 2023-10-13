<?php

namespace App\Http\Controllers;

use App\Models\Expenditure;
use Illuminate\Http\Request;

class ExpenditureController extends Controller
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
        return view('admin.expenditure.expenditure');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function api() 
    {
        $expenditures = Expenditure::all();
        $datatables = datatables()->of($expenditures)
            ->addIndexColumn()
            ->addColumn('nominal', function ($expenditures) {
                return format_uang($expenditures->nominal);
            })
            ->addColumn('created_at', function ($expenditures) {
                return tanggal_indonesia($expenditures->created_at, false);
            });

        return $datatables->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'description' => ['required'],
            'nominal' => ['required'],
        ]);

        Expenditure::create($request->all());

        return redirect('expenditures');
    }

    /**
     * Display the specified resource.
     */
    public function show(Expenditure $expenditure)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expenditure $expenditure)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expenditure $expenditure)
    {
        $this->validate($request, [
            'description' => ['required'],
            'nominal' => ['required'],
        ]);

        $expenditure->update($request->all());

        return redirect('expenditures');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expenditure $expenditure)
    {
        $expenditure->delete();
    }
}
