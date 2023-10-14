<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function test_spatie()
    {
        // $role = Role::create(['name' => 'admin']);
        // $permission = Permission::create(['name' => 'View Menu']);

        // $role->givePermissionTo($permission);
        // $permission->assignRole($role);

        // $user = auth()->user();
        // $user->assignRole('admin');

        $user = User::with('roles')->get();

        // $user = User::where('id', 4)->first();
        // $user->removeRole('writer');

        return $user;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $total_product = Product::count();
        $total_member = Member::count();
        $total_sale = Sale::count();
        $total_purchase = Purchase::count();

        return view('home', compact('total_product', 'total_member', 'total_sale', 'total_purchase'));
    }
}
