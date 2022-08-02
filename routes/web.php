<?php

use App\Http\Controllers\CustomerCategoriesController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Models\CustomerModel;
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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::resource('/products', ProductController::class);
Route::resource('/customers', CustomerController::class);
Route::resource('/customer_categories', CustomerCategoriesController::class);
