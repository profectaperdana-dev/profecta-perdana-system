<?php

use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\CheckStockController;
use App\Http\Controllers\CustomerAreasController;
use App\Http\Controllers\CustomerCategoriesController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\FilesController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SalesOrderController;
use App\Http\Controllers\SecondProductController;
use App\Http\Controllers\SendEmailController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SubMaterialController;
use App\Http\Controllers\SubTypeController;
use App\Http\Controllers\SuppliersController;
use App\Http\Controllers\UomController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\ClaimController;
use App\Models\CustomerModel;
use App\Models\MaterialModel;
use App\Models\SalesOrderModel;
use Illuminate\Support\Facades\Artisan;
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

Route::get('/artisan', function () {
    Artisan::call('route:clear');
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
    Route::post('/sales_orders/{id}/verify', [SalesOrderController::class, 'verify']);
    Route::get('/need_approval', [SalesOrderController::class, 'soNeedApproval']);
    Route::get('/customers/getProvince', [CustomerController::class, 'getProvince']);
    Route::get('/customers/getCity/{province_id}', [CustomerController::class, 'getCity']);
    Route::get('/customers/getDistrict/{city_id}', [CustomerController::class, 'getDistrict']);
    Route::get('/customers/getVillage/{district_id}', [CustomerController::class, 'getVillage']);
    Route::get('/invoice', [SalesOrderController::class, 'getInvoiceData']);
    Route::get('/invoice/{id}/invoice_with_ppn', [SalesOrderController::class, 'printInoiceWithPpn']);
    Route::get('/invoice/{id}/delivery_order', [SalesOrderController::class, 'deliveryOrder']);
    Route::post('/invoice/{id}/update_payment', [SalesOrderController::class, 'updatePaid']);
    Route::get('/sales_orders/approve/{id}', [SalesOrderController::class, 'approve']);
    Route::get('/products/selectCost/{id}', [ProductController::class, 'selectCost']);
    Route::get('/invoice/manage_payment', [SalesOrderController::class, 'paidManagement']);
    Route::get('/send_email/{id}', [SendEmailController::class, 'index']);
    Route::get('/sales_orders/reject/{id}', [SalesOrderController::class, 'reject']);
    Route::get('/customers/getTotalCredit/{id}', [CustomerController::class, 'getTotalCredit']);
    Route::get('/all_purchase_orders', [PurchaseOrderController::class, 'getPO']);
    Route::get('/file_do', [FilesController::class, 'getDO']);
    Route::get('/po/{id}/print', [PurchaseOrderController::class, 'printPO']);
    Route::get('/send_email_po/{id}', [SendEmailController::class, 'sendPo']);
    Route::get('/file_po', [FilesController::class, 'getFilePo']);
    Route::get('/notification/getAll/', [NotificationController::class, 'getAll']);
    Route::get('/read_notif/{id}/', [NotificationController::class, 'readMessage']);
    Route::post('/purchase_orders/{id}/validate', [PurchaseOrderController::class, 'validation']);
    Route::post('/purchase_orders/{id}/update_po', [PurchaseOrderController::class, 'updatePO']);
    Route::get('/purchase_orders/receiving', [PurchaseOrderController::class, 'receivingPO']);
    Route::get('/read_all_notif/{id}', [NotificationController::class, 'readAll']);
    Route::get('/invoice/getTotalInstalment/{id}', [SalesOrderController::class, 'getTotalInstalment']);
    Route::get('/print_history_payment/{id}', [SalesOrderController::class, 'printHistoryPayment']);
    Route::get('/analytics', [AnalysisController::class, 'index']);
    Route::get('/salesman_chart', [AnalysisController::class, 'salesmanChart']);
    Route::get('/product_chart', [AnalysisController::class, 'productChart']);
    Route::get('/report_purchase', [ReportController::class, 'report_po']);
    Route::get('/report_sales/', [ReportController::class, 'index']);
    Route::get('/sales_order/selectReturn', [SalesOrderController::class, 'selectReturn']);
    Route::get('/history_claim/', [ClaimController::class, 'historyClaim']);
    Route::get('/report_claim/', [ReportController::class, 'reportClaim']);

    Route::get('/data_by_sales/', [AnalysisController::class, 'dataBySales']);
    Route::post('/invoice/{id}/edit_superadmin', [SalesOrderController::class, 'editSuperadmin']);
    Route::prefix('return')->group(function () {
        Route::get('/', [ReturnController::class, 'index']);
        Route::get('/{id}', [ReturnController::class, 'create']);
        Route::post('/store', [ReturnController::class, 'store']);
    });

    Route::group(['middleware' => 'can:isSuperAdmin'], function () {
        Route::post('/purchase_orders/{id}/manage', [PurchaseOrderController::class, 'manage']);

        Route::resource('/customer_categories', CustomerCategoriesController::class);
        Route::resource('/customer_areas', CustomerAreasController::class);
        Route::resource('/warehouses', WarehouseController::class);
        Route::resource('/supliers', SuppliersController::class);
        Route::resource('/jobs', JobController::class);
        Route::resource('/roles', RoleController::class);
        Route::resource('/users', UserController::class);
        Route::resource('/product_materials', MaterialController::class);
        Route::resource('/product_sub_materials', SubMaterialController::class);
        Route::resource('/product_uoms', UomController::class);
        Route::resource('/product_sub_types', SubTypeController::class);
        Route::resource('/products', ProductController::class);
        Route::resource('/second_product', SecondProductController::class);
    });

    Route::resource('/customers', CustomerController::class);
    Route::resource('/profiles', ProfileController::class);
    Route::patch('/profiles/{id}/photo', [ProfileController::class, 'changePhoto']);
    Route::patch('/profiles/{id}/password', [ProfileController::class, 'changePassword']);
    Route::resource('/stocks', StockController::class);
    Route::resource('/discounts', DiscountController::class);
    Route::resource('/sales_order', SalesOrderController::class);
    Route::resource('/check_stock', CheckStockController::class);
    Route::resource('/purchase_orders', PurchaseOrderController::class);
    Route::resource('/file_invoice', FilesController::class);
    Route::resource('/claim', ClaimController::class);
});

Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate']);
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
