<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AccountingController;
use App\Http\Controllers\AccountSubController;
use App\Http\Controllers\AccountSubTypeController;
use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\AssetCategoryController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\CarController;
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
use App\Http\Controllers\DirectSalesController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\MotorController;
use App\Http\Controllers\ProductTradeInController;
use App\Http\Controllers\ProspectiveEmployeeController;
use App\Http\Controllers\SecondSaleController;
use App\Http\Controllers\StockMutationController;
use App\Http\Controllers\ValueAddedTaxController;
use App\Models\CustomerModel;
use App\Models\MaterialModel;
use App\Models\SalesOrderModel;
use App\Http\Controllers\WarehouseTypeController;
use App\Models\AssetCategoryModel;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Stmt\Return_;

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

Route::post('/prospective_employees/store_form', [ProspectiveEmployeeController::class, 'store_form']);
Route::get('/customers/getProvince', [CustomerController::class, 'getProvince']);
Route::get('/customers/getCity/{province_id}', [CustomerController::class, 'getCity']);
Route::get('/customers/getDistrict/{city_id}', [CustomerController::class, 'getDistrict']);
Route::get('/prospective_employees/fill_form/{any}', [ProspectiveEmployeeController::class, 'fill_form']);
Route::get('/form_filled_successfully', [ProspectiveEmployeeController::class, 'form_filled_successfully']);
Route::get('/prospective_employees/print_data/{any}', [ProspectiveEmployeeController::class, 'print_data']);

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
    Route::get('/report_return_invoice/', [ReportController::class, 'report_return']);
    Route::get('/report_return_purchases/', [ReportController::class, 'report_return_purchase']);
    Route::get('/sales_order/getQtyDetail', [SalesOrderController::class, 'getQtyDetail']);
    Route::get('/sales_order/getAllDetail', [SalesOrderController::class, 'getAllDetail']);
    Route::get('/car_brand/select/{id}', [ClaimController::class, 'select']);
    Route::get('/purchase_order/selectReturn', [PurchaseOrderController::class, 'selectReturn']);
    Route::get('/purchase_order/getQtyDetail', [PurchaseOrderController::class, 'getQtyDetail']);
    Route::get('/purchase_order/getAllDetail', [PurchaseOrderController::class, 'getAllDetail']);
    Route::get('/data_by_sales/', [AnalysisController::class, 'dataBySales']);
    Route::post('/invoice/{id}/edit_superadmin', [SalesOrderController::class, 'editSuperadmin']);
    Route::get('/pdf_claim_accu/{id}', [ClaimController::class, 'pdfClaimAccu']);
    Route::get('/pdf_claim_accu_finish/{id}', [ClaimController::class, 'pdfClaimAccuFinish']);
    Route::get('send_early_accu_claim/{id}', [SendEmailController::class, 'sendEarlyAccuClaim']);
    Route::get('send_early_accu_claim_finish/{id}', [SendEmailController::class, 'sendEarlyAccuClaimFinish']);
    Route::get('claim_tyre', [ClaimController::class, 'indexTyre']);
    Route::get('claim_tyre/create', [ClaimController::class, 'createTyre']);
    Route::post('claim_tyre/store', [ClaimController::class, 'storeTyre']);
    Route::delete('claim_tyre_del/{id}', [ClaimController::class, 'delTyre']);
    Route::post('mutasi_claim/{id}', [ClaimController::class, 'mutasiClaim']);
    Route::get('motorcycle_type', [MotorController::class, 'motorcycleType']);
    Route::post('motorcycle_type/store', [MotorController::class, 'storeMotorcycleType']);
    Route::post('motorcycle_type/update/{id}', [MotorController::class, 'updateMotorcycleType']);
    Route::delete('motorcycle_type/delete/{id}', [MotorController::class, 'deleteMotorcycleType']);
    Route::get('create/trade_in', [ProductTradeInController::class, 'product_trade_in']);
    Route::get('all_product_trade_in', [ProductTradeInController::class, 'product_trade_in_all']);
    Route::post('trade_in/store', [ProductTradeInController::class, 'storeTradeIn']);
    Route::get('trade_invoice', [ProductTradeInController::class, 'tradeInvoice']);
    Route::get('send_email_trade_invoice/{id}', [SendEmailController::class, 'sendTradeInvoice']);
    Route::get('trade_invoice/{id}/print', [ProductTradeInController::class, 'printTradeInvoice']);
    Route::get('trade_invoice/print_struk/{id}/print', [ProductTradeInController::class, 'printStruk']);
    Route::get('second_sale/print_struk/{id}/print', [SecondSaleController::class, 'printStruk']);
    Route::get('report_trade_in', [ReportController::class, 'reportTradeIn']);
    Route::get('jurnal', [AccountingController::class, 'jurnal']);
    Route::get('/sub_account/select/{id}', [AccountingController::class, 'select']);
    Route::get('/sub_type_account/select/', [AccountingController::class, 'select_type']);
    Route::get('motocycle_brand/select/{id}', [MotorController::class, 'select']);
    Route::get('district/selectAll', [DirectSalesController::class, 'select']);
    Route::post('expenses/store', [AccountingController::class, 'storeExpenses']);
    Route::get('/retail_second_products/select', [SecondSaleController::class, 'select']);
    Route::get('/retail_second_products/cekQty/{id_product}', [SecondSaleController::class, 'cekQty']);
    Route::get('prospective_employees/create_code', [ProspectiveEmployeeController::class, 'createCode']);
    Route::get('profit_loss', [AccountingController::class, 'profit_loss']);
    Route::get('stock_c01', [StockController::class, 'stock_c01']);
    Route::get('stock_c02', [StockController::class, 'stock_c02']);
    Route::get('stock_c03', [StockController::class, 'stock_c03']);
    Route::get('stock_ss01', [StockController::class, 'stock_ss01']);
    Route::get('stock_supplier', [StockController::class, 'stock_supplier']);
    Route::get('/tradein/selectCost/{id}', [ProductTradeInController::class, 'selectCost']);
    Route::get('trade_invoice/{id}/edit', [ProductTradeInController::class, 'editTradeInvoice']);
    Route::post('/trade_in/{id}/edit_superadmin', [ProductTradeInController::class, 'editSuperadmin']);


    Route::prefix('expenses')->group(function () {
        Route::get('/create', [AccountingController::class, 'createExpenses']);
        Route::post('/store', [AccountingController::class, 'store_expense']);
    });

    Route::prefix('depreciation')->group(function () {
        Route::get('/', [AccountingController::class, 'depreciation']);
    });

    Route::prefix('retail')->group(function () {
        Route::get('/', [DirectSalesController::class, 'index']);
        Route::get('/create', [DirectSalesController::class, 'create']);
        Route::post('/store', [DirectSalesController::class, 'store']);
        Route::get('/credit', [DirectSalesController::class, 'credit']);
        Route::get('/print_invoice/{id}', [DirectSalesController::class, 'print_invoice']);
        Route::get('/print_do/{id}', [DirectSalesController::class, 'print_do']);
        Route::get('/send_mail/{id}', [SendEmailController::class, 'send_mail_retail']);
        Route::get('/mark_as_paid/{id}', [DirectSalesController::class, 'mark_as_paid']);
        Route::get('/selectProductAll', [DirectSalesController::class, 'selectProductAll']);
        Route::post('/{id}/update_retail', [DirectSalesController::class, 'update_retail']);
        Route::get('/search', [DirectSalesController::class, 'search']);
        Route::get('/selectById', [DirectSalesController::class, 'selectById']);
        Route::get('/print_struk/{id}', [DirectSalesController::class, 'PrintStruk']);
    });

    Route::prefix('cars_type')->group(function () {
        Route::get('/', [CarController::class, 'index_type']);
        Route::post('/store', [CarController::class, 'store_type']);
        Route::post('/update_type/{id}', [CarController::class, 'update_type']);
        Route::delete('/delete/{id}', [CarController::class, 'delete_type']);
    });

    Route::prefix('return')->group(function () {
        Route::get('/', [ReturnController::class, 'index']);
        Route::get('/{id}', [ReturnController::class, 'create']);
        Route::post('/store', [ReturnController::class, 'store']);
        Route::post('/{id}/update_return', [ReturnController::class, 'update_return']);
        Route::get('/{id}/print', [ReturnController::class, 'print_return']);
        Route::get('/{id}/send_email', [SendEmailController::class, 'send_return']);
    });

    Route::prefix('return_purchase')->group(function () {
        Route::get('/', [ReturnController::class, 'index_purchase']);
        Route::get('/{id}', [ReturnController::class, 'create_purchase']);
        Route::post('/store_purchase', [ReturnController::class, 'store_purchase']);
        Route::post('/{id}/update_return_purchase', [ReturnController::class, 'update_return_purchase']);
        Route::get('/{id}/print', [ReturnController::class, 'print_return_purchase']);
    });

    Route::prefix('stock_mutation')->group(function () {
        Route::get('/', [StockMutationController::class, 'index']);
        Route::get('/create', [StockMutationController::class, 'create']);
        Route::get('/approval', [StockMutationController::class, 'approval']);
        Route::post('/{id}/approve_mutation', [StockMutationController::class, 'approve_mutation']);
        Route::get('/select', [StockMutationController::class, 'select']);
        Route::get('/getQtyDetail', [StockMutationController::class, 'getQtyDetail']);
        Route::post('/store', [StockMutationController::class, 'store']);
        Route::post('/{id}/update_mutation', [StockMutationController::class, 'update_mutation']);
        Route::get('/{id}/print_do', [StockMutationController::class, 'print_do']);
    });

    Route::prefix('value_added_tax')->group(function () {
        Route::get('/', [ValueAddedTaxController::class, 'index']);
        Route::post('/{id}/update', [ValueAddedTaxController::class, 'update']);
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
        Route::resource('/motorcycle', MotorController::class);
        Route::resource('/cars', CarController::class);
        Route::resource('/trade_in', ProductTradeInController::class);
        Route::resource('/account', AccountController::class);
        Route::resource('/account_sub', AccountSubController::class);
        Route::resource('/account_sub_type', AccountSubTypeController::class);
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
    Route::resource('/warehouse_types', WarehouseTypeController::class);
    Route::resource('/asset', AssetController::class);
    Route::resource('/retail_second_products', SecondSaleController::class);
    Route::resource('/prospective_employees', ProspectiveEmployeeController::class, ['except' => ['show', 'store_form']]);
    Route::resource('/asset_category', AssetCategoryController::class);
    Route::resource('/employee', EmployeeController::class);
});

Route::group(['middleware' => 'guest'], function () {

    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate']);
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
