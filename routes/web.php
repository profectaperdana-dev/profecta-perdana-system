<?php

use App\Http\Controllers\CheckStockController;
use App\Http\Controllers\CustomerAreasController;
use App\Http\Controllers\CustomerCategoriesController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SalesOrderController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SubMaterialController;
use App\Http\Controllers\SubTypeController;
use App\Http\Controllers\SuppliersController;
use App\Http\Controllers\UomController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseController;
use App\Models\CustomerModel;
use App\Models\MaterialModel;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::group(['middleware' => 'auth'], function () {
    //ajax route
    Route::get('/products/cekproduct', [StockController::class, 'cekProduk']);
    Route::get('/product_sub_materials/select/{id}', [SubMaterialController::class, 'select']);
    Route::get('/product_sub_types/select/{id}', [SubTypeController::class, 'select']);
    Route::get('/product_sub_types/selectAll', [SubTypeController::class, 'selectAll']);
    Route::get('/products/select', [ProductController::class, 'select']);
    Route::get('/products/selectAll', [ProductController::class, 'selectAll']);
    Route::get('/discounts/select/{customer_id}/{product_id}', [DiscountController::class, 'select']);
    Route::get('/products/select_without', [ProductController::class, 'selectWithout']);
    Route::get('/recent_sales_order', [SalesOrderController::class, 'getRecentData']);
    Route::put('/updateso/{id}/editso', [SalesOrderController::class, 'updateSo']);
    Route::get('/cek_jam', [SalesOrderController::class, 'cekJam']);
    Route::get('/logout', [LoginController::class, 'logout']);
    Route::get('/edit_sales_order/{id}', [SalesOrderController::class, 'editSo']);
    Route::get('/stocks/cekQty/{product_id}', [StockController::class, 'cekQty']);
    Route::get('/sales_orders/verify/{id}', [SalesOrderController::class, 'verify']);
    Route::get('/need_approval', [SalesOrderController::class, 'getInvoiceData']);
    Route::get('/customers/getProvince', [CustomerController::class, 'getProvince']);
    Route::get('/customers/getCity/{province_id}', [CustomerController::class, 'getCity']);
    Route::get('/customers/getDistrict/{city_id}', [CustomerController::class, 'getDistrict']);
    Route::get('/customers/getVillage/{district_id}', [CustomerController::class, 'getVillage']);
    Route::get('/edit_product/{id}', [SalesOrderController::class, 'editProduct']);
    route::get('/delete_product/{id_so}/{id_sod}', [SalesOrderController::class, 'deleteProduct']);
    route::put('/update_product/{id}/edit_product', [SalesOrderController::class, 'updateProduct']);



    Route::resource('/roles', RoleController::class);
    Route::resource('/products', ProductController::class);
    Route::resource('/customers', CustomerController::class);
    Route::resource('/customer_categories', CustomerCategoriesController::class);
    Route::resource('/customer_areas', CustomerAreasController::class);
    Route::resource('/product_uoms', UomController::class);
    Route::resource('/product_materials', MaterialController::class);
    Route::resource('/product_sub_materials', SubMaterialController::class);
    Route::resource('/warehouses', WarehouseController::class);
    Route::resource('/users', UserController::class)->middleware('can:isSuperAdmin');
    Route::resource('/profiles', ProfileController::class);
    Route::patch('/profiles/{id}/photo', [ProfileController::class, 'changePhoto']);
    Route::patch('/profiles/{id}/password', [ProfileController::class, 'changePassword']);
    Route::resource('/product_sub_types', SubTypeController::class);
    Route::resource('/supliers', SuppliersController::class);
    Route::resource('/stocks', StockController::class);
    Route::resource('/discounts', DiscountController::class);
    Route::resource('/sales_order', SalesOrderController::class);
    Route::resource('/check_stock', CheckStockController::class);
});

Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate']);
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
