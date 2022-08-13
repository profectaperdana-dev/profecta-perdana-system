<?php

use App\Http\Controllers\CustomerAreasController;
use App\Http\Controllers\CustomerCategoriesController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
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
    return view('welcome');
});

Route::group(['middleware' => 'auth'], function () {
    //ajax route
    Route::get('/product_sub_materials/select/{id}', [SubMaterialController::class, 'select']);
    Route::get('/product_sub_types/select/{id}', [SubTypeController::class, 'select']);
    Route::get('/products/select', [ProductController::class, 'select']);
    Route::get('/products/coba', [ProductController::class, 'coba']);

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
    Route::get('/logout', [LoginController::class, 'logout']);
    Route::patch('/profiles/{id}/photo', [ProfileController::class, 'changePhoto']);
    Route::resource('/product_sub_types', SubTypeController::class);
    Route::resource('/supliers', SuppliersController::class);
    Route::resource('/discounts', DiscountController::class);
});

Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate']);
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
