<?php

use App\Exports\SalesExport;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SalesController;
use App\Http\Middleware\RedirectIfEmployee;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

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

// Route::get('/tab', function () {
//     return view('pages.product.index');
// });
Route::middleware('auth')->group(function () {

    Route::get('/export-sales', function () {
        $startDate = request()->input('start_date', now()->subMonth());
        $endDate = request()->input('end_date', now());
        return Excel::download(new SalesExport($startDate, $endDate), 'sales.xlsx');
    })->name('export.sales');

Route::get('/products', [ProductController::class, 'index_prod'])->name('product_table');
Route::get('/products-add', [ProductController::class, 'create_prod'])->name('product_add');
Route::post('/products-add', [ProductController::class, 'store_prod'])->name('product_store');
Route::get('/products-edit/{id}', [ProductController::class, 'edit_prod'])->name('product_edit');
Route::put('/products-edit/{id}', [ProductController::class, 'update_prod'])->name('product_update');
Route::put('/products-edit/{id}/delete', [ProductController::class, 'delete_prod'])->name('product_delete');

Route::get('/customers', [GeneralController::class, 'index_cust'])->name('customer_table');
Route::get('/customers-add', [GeneralController::class, 'create_cust'])->name('customer_add');
Route::post('/customers-add', [GeneralController::class, 'store_cust'])->name('customer_store');
Route::get('/customers-edit/{id}', [GeneralController::class, 'edit_cust'])->name('customer_edit');
Route::put('/customers-edit/{id}', [GeneralController::class, 'update_cust'])->name('customer_update');
Route::put('/customers-edit/{id}/delete', [GeneralController::class, 'delete_cust'])->name('customer_delete');

Route::get('/sales', [SalesController::class, 'index_sales'])->name('sales_table');

Route::get('/sales_details', [SalesController::class, 'index_sales_detail'])->name('sales_detail_table');

Route::get('/', [SalesController::class, 'create_transaction'])->name('transaction_create');
Route::post('/store-transaction', [SalesController::class, 'store_transaction'])->name('transaction_store');
Route::get('/transactions/{id}/edit', [SalesController::class, 'edit_transaction'])->name('transaction_edit');
Route::put('/transactions/{id}/edit', [SalesController::class, 'update_transaction'])->name('transaction_update');
Route::delete('/transactions/{id}/delete', [SalesController::class, 'destroy_transaction'])->name('transaction_destroy');

});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login_page');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware([RedirectIfEmployee::class])->group(function () {
    Route::get('/register', [AuthController::class, 'index_register'])->name('account_table');
    Route::get('/register-new', [AuthController::class, 'create_register'])->name('account_create');
    Route::post('/register-new', [AuthController::class, 'store_register'])->name('account_store');
    Route::get('/re-register/{id}', [AuthController::class, 'edit_register'])->name('account_edit');
    Route::put('/re-register/{id}', [AuthController::class, 'update_register'])->name('account_update');

    Route::get('/acc-histories', [AuthController::class, 'register_history'])->name('account_history');
});

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');