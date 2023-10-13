<?php

use App\Http\Controllers\PurchaseDetailController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SaleDetailController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::resource('/categorys', App\Http\Controllers\CategoryController::class);

Route::resource('/products', App\Http\Controllers\ProductController::class);
Route::post('/products/print-barcode', [App\Http\Controllers\ProductController::class, 'PrintBarcode'])->name('products.print_barcode');
Route::post('/products/delete-selected', [App\Http\Controllers\ProductController::class, 'deleteSelected'])->name('products.delete_selected');

Route::resource('/members', App\Http\Controllers\MemberController::class);
Route::post('/products/print-card', [App\Http\Controllers\MemberController::class, 'printCard'])->name('members.print_card');
Route::post('/members/delete-selected', [App\Http\Controllers\MemberController::class, 'deleteSelected'])->name('members.delete_selected');

Route::resource('/suppliers', App\Http\Controllers\SupplierController::class);

Route::resource('/expenditures', App\Http\Controllers\ExpenditureController::class);

Route::get('/purchases/{id}/create', [App\Http\Controllers\PurchaseController::class, 'create'])->name('purchases.create');
Route::post('/purchases/store', [PurchaseController::class, 'store'])->name('purchases.store');
Route::resource('/purchases', App\Http\Controllers\PurchaseController::class)
    ->except('create');

Route::resource('/purchases_detail', App\Http\Controllers\PurchaseDetailController::class)
    ->except('create', 'edit', 'show');
Route::get('/purchases_detail/loadform/{discont}/{total}', [PurchaseDetailController::class, 'loadForm'])->name('purchases_detail.load_form');
Route::get('/purchases_detail/{id}/data', [App\Http\Controllers\PurchaseDetailController::class, 'data'])->name('purchases_detail.data');

Route::get('/sale/data', [SaleController::class, 'data'])->name('sale.data');
Route::get('/sale', [SaleController::class, 'index'])->name('sale.index');
Route::get('/sale/{id}', [SaleController::class, 'show'])->name('sale.show');

Route::get('/transaction/new', [App\Http\Controllers\SaleController::class, 'create'])->name('transaction.new');
Route::post('/transaction/save', [App\Http\Controllers\SaleController::class, 'store'])->name('transaction.save');
Route::get('/transaction/end', [App\Http\Controllers\SaleController::class, 'end'])->name('transaction.end');
Route::get('/transaction/nota-kecil', [SaleController::class, 'notaKecil'])->name('transaction.nota_kecil');
Route::get('/transaction/nota-besar', [SaleController::class, 'notaBesar'])->name('transaction.nota_besar');
Route::get('/transaction', [App\Http\Controllers\SaleController::class, 'index'])->name('transaction.index');

Route::get('/transaction/loadform/{discont}/{total}/{accepted}', [SaleDetailController::class, 'loadForm'])->name('transaction.load_form');
Route::get('/transaction/{id}/data', [App\Http\Controllers\SaleDetailController::class, 'data'])->name('transaction.data');
Route::resource('/transaction', App\Http\Controllers\SaleDetailController::class)
    ->except('show');

Route::get('/report', [ReportController::class, 'index'])->name('report.index');
Route::get('/report/data/{awal}/{akhir}', [ReportController::class, 'data'])->name('report.data');
Route::get('/report/pdf/{awal}/{akhir}', [ReportController::class, 'exportPDF'])->name('report.export_pdf');


Route::get('/api/categorys', [App\Http\Controllers\CategoryController::class, 'api']);
Route::get('/api/products', [App\Http\Controllers\ProductController::class, 'api']);
Route::get('/api/members', [App\Http\Controllers\MemberController::class, 'api']);
Route::get('/api/suppliers', [App\Http\Controllers\SupplierController::class, 'api']);
Route::get('/api/expenditures', [App\Http\Controllers\ExpenditureController::class, 'api']);
Route::get('/api/purchases', [App\Http\Controllers\PurchaseController::class, 'api']);
Route::get('/api/sales', [App\Http\Controllers\SaleController::class, 'api']);