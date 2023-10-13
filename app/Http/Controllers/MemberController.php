<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class MemberController extends Controller
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
        $memberss = Member::all();

        return view('admin.member.member', compact('memberss'));
    }

    public function api() 
    {
        $members = Member::all();
        return datatables()->of($members)
            ->addIndexColumn()
            ->addColumn('select_all', function ($members) {
                return '<input type="checkbox" name="id[]" value="'. $members->id .'">';
            })
            ->addColumn('member_code', function ($members) {
                return '<span class="badge badge-success">'. $members->member_code.'</span>';
            })
            ->rawColumns(['select_all', 'member_code'])
            ->make(true);
    }

    public function printCard(Request $request)
    {
        $datamember = collect(array());
        foreach ($request->id as $id) {
            $member = Member::find($id);
            $datamember[] = $member;
        }

        $datamember = $datamember->chunk(2);

        $setting = Setting::first();
        $no  = 1;
        $pdf = Pdf::loadView('admin.member.cetak', compact('datamember', 'no', 'setting'));
        $pdf->setPaper(array(0, 0, 566.93, 850.39), 'potrait');
        return $pdf->stream('member.pdf');
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
        
        $member = Member::latest()->first() ?? new Member();
        $member_code = (int) $member->member_code +1;

        $member = new Member();
        $member->member_code = add_nol($member_code, 5);
        $member->member_name = $request->member_name;
        $member->address = $request->address;
        $member->phone = $request->phone;
        $member->save();

        return redirect('members');
    }

    public function deleteSelected(Request $request)
    {
        foreach ($request->id as $member_id) {
            $member = Member::find($member_id);
            $member->delete();
        }

        return response(null, 204);
    }

    /**
     * Display the specified resource.
     */
    public function show(Member $member)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Member $member)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Member $member)
    {
        $this->validate($request, [
            'member_name' => ['required'],
            'address' => ['required'],
            'phone' => ['required'],
        ]);

        $member->update($request->all());

        return redirect('members');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member)
    {
        $member->delete();
    }
}
