<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AccountingController;
use App\Http\Controllers\AccountSubController;
use App\Http\Controllers\AccountSubTypeController;
use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\AssetCategoryController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\AttendancesController;
use App\Http\Controllers\ActionPlansController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\CheckStockController;
use App\Http\Controllers\CustomerAreasController;
use App\Http\Controllers\CustomerCategoriesController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\FilesController;
use App\Http\Controllers\ItemPromotionController;
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

use App\Http\Controllers\Cms\AboutController;
use App\Http\Controllers\Cms\AreaController;
use App\Http\Controllers\Cms\AuthController;
use App\Http\Controllers\Cms\BlogController;
use App\Http\Controllers\Cms\ContactController;
use App\Http\Controllers\Cms\FaqController;
use App\Http\Controllers\Cms\GalleryController;
use App\Http\Controllers\Cms\JobVacanciesController;
use App\Http\Controllers\Cms\TeamController;
use App\Http\Controllers\Cms\PortfolioController;
use App\Http\Controllers\Cms\HomePageController;
use App\Http\Controllers\Cms\ProductController as CmsProductController;

use App\Http\Controllers\DailyActivityController;
use App\Http\Controllers\DeliveryHistoriesController;
use App\Http\Controllers\DirectSalesController;
use App\Http\Controllers\DocumentUpdateController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\MotorController;
use App\Http\Controllers\NationalDayController;
use App\Http\Controllers\ProductTradeInController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ProspectiveEmployeeController;
use App\Http\Controllers\SecondSaleController;
use App\Http\Controllers\StockMutationController;
use App\Http\Controllers\TyreDotController;
use App\Http\Controllers\VacationController;
use App\Http\Controllers\TripController;

use App\Http\Controllers\Finance\JournalController;
use App\Http\Controllers\Finance\CoaController;
use App\Http\Controllers\Finance\ReportController as FinanceReportController;

use App\Http\Controllers\ValueAddedTaxController;
use App\Models\CustomerModel;
use App\Models\MaterialModel;
use App\Models\SalesOrderModel;
use App\Http\Controllers\WarehouseTypeController;
use App\Models\AssetCategoryModel;
use App\Models\DirectSalesModel;
use App\Models\NationalDayModel;
use App\Models\PurchaseOrderModel;
use App\Models\UomModel;
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
//! route for form employee
Route::post('/candidate_employee/store_form', [ProspectiveEmployeeController::class, 'store_form']);
Route::get('/customers/getProvince', [CustomerController::class, 'getProvince']);
Route::get('/customers/getCity/{province_id}', [CustomerController::class, 'getCity']);
Route::get('/customers/getDistrict/{city_id}', [CustomerController::class, 'getDistrict']);
Route::get('/prospective_employees/fill_form/{any}', [ProspectiveEmployeeController::class, 'fill_form']);
Route::get('/form_filled_successfully', [ProspectiveEmployeeController::class, 'form_filled_successfully']);
Route::get('/prospective_employees/print_data/{any}', [ProspectiveEmployeeController::class, 'print_data']);
Route::get('/asset/information/{id}', [AssetController::class, 'information']);

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/artisan', function () {
    Artisan::call('route:clear');
});

Route::group(['middleware' => 'auth'], function () {
    //** CMS */
    Route::prefix('cms')->group(function () {
        //**API */
        Route::prefix('api')->group(function () {
            // **FAQ
            Route::post('/faq/store', [FaqController::class, 'store']);
            Route::delete('/faq/{id}/delete', [FaqController::class, 'delete']);
            Route::put('/faq/{id}/edit', [FaqController::class, 'edit']);

            // **Gallery
            Route::post('/category_gallery/store', [GalleryController::class, 'store_category']);
            Route::delete('/category_gallery/{id}/delete', [GalleryController::class, 'delete_category']);
            Route::put('/category_gallery/{id}/edit', [GalleryController::class, 'edit_category']);
            Route::post('/gallery/store', [GalleryController::class, 'store']);
            Route::delete('/gallery/{id}/delete', [GalleryController::class, 'delete']);
            Route::put('/gallery/{id}/edit', [GalleryController::class, 'edit']);

            // **Contact
            Route::get('/contact/getDataByArea', [ContactController::class, 'get_data_by_area']);
            Route::post('/contact/store', [ContactController::class, 'store']);

            // **Area
            Route::post('/area/store', [AreaController::class, 'store']);
            Route::put('/area/{id}/edit', [AreaController::class, 'edit']);
            Route::delete('/area/{id}/delete', [AreaController::class, 'delete']);

            // **About
            Route::post('/about/store', [AboutController::class, 'store']);

            // **Team
            Route::post('/team/save', [TeamController::class, 'save']);

            // **Portfolio
            Route::post('/portfolio/store', [PortfolioController::class, 'store']);
            Route::delete('/portfolio/{id}/delete', [PortfolioController::class, 'delete']);
            Route::put('/portfolio/{id}/edit', [PortfolioController::class, 'edit']);

            // **Blog
            Route::post('/category_blog/store', [BlogController::class, 'store_category']);
            Route::delete('/category_blog/{id}/delete', [BlogController::class, 'delete_category']);
            Route::put('/category_blog/{id}/edit', [BlogController::class, 'edit_category']);
            Route::post('/blog/savedraft', [BlogController::class, 'save_as_draft'])->name('save_as_draft');
            Route::post('/blog/publish', [BlogController::class, 'publish'])->name('publish');
            Route::delete('/blog/{id}/delete', [BlogController::class, 'delete']);

            //**Product
            Route::post('/product/save', [CmsProductController::class, 'store']);

            //**HomePage
            Route::post('/homepage/store', [HomePageController::class, 'store']);

            //* Live Chat Generate Key
            Route::post('/live_chat/generate', [AuthController::class, 'generate']);
        });

        // **portfolio
        // Route::get('/product_select', [PortfolioCMSController::class, 'getProduct']);
        // Route::get('/portfolio', [PortfolioCMSController::class, 'index']);
        // Route::post('/portfolio/store', [PortfolioCMSController::class, 'store']);
        // Route::post('/portfolio/{id}/edit', [PortfolioCMSController::class, 'update']);
        // Route::get('/portfolio/{id}/delete', [PortfolioCMSController::class, 'destroy']);
        // **blog Categories
        // **blog
        Route::get('/blog', [BlogController::class, 'index']);
        Route::get('/blog/write', [BlogController::class, 'write']);
        Route::get('/blog/{slug}/read', [BlogController::class, 'read']);
        Route::get('/blog/{slug}/edit', [BlogController::class, 'edit']);
        // **FAQ
        Route::get('/faq', [FaqController::class, 'index']);
        // **Gallery
        Route::get('/gallery', [GalleryController::class, 'index']);
        // **Contact
        Route::get('/contact', [ContactController::class, 'index']);
        // **About
        Route::get('/about', [AboutController::class, 'index']);
        // **Team
        Route::get('/team', [TeamController::class, 'index']);
        // **Portfolio
        Route::get('/portfolio', [PortfolioController::class, 'index']);
        // **HomePage
        Route::get('/homepage', [HomePageController::class, 'index']);
        // **Product
        Route::get('/product', [CmsProductController::class, 'index']);
        // **Live Chat
        Route::get('/live_chat/generate_key', [AuthController::class, 'index']);
    });
    
    Route::prefix('job_vacancies')->group(function () {
        Route::get('/', [JobVacanciesController::class, 'index']);
        Route::get('/create', [JobVacanciesController::class, 'create']);
        Route::post('/store', [JobVacanciesController::class, 'store']);
        Route::post('{id}/edit', [JobVacanciesController::class, 'update']);
        Route::get('{id}/delete', [JobVacanciesController::class, 'destroy']);
    });

    //** CANDIDATE */
    Route::prefix('applicant_data')->group(function () {
        Route::get('/', [CandidateController::class, 'index']);
        Route::get('/question/select/{id}', [CandidateController::class, 'getQuestion']);
        Route::get('/answer/select/{id}', [CandidateController::class, 'getAnswer']);
    });
    
    // ** FINANCE */
    Route::prefix('finance')->group(function () {
        Route::get('/get_category_promotion', [ItemPromotionController::class, 'getCategory']);

        // ** COA
        Route::prefix('coa')->group(function () {
            Route::get('/categories', [CoaController::class, 'indexCoaCategories']);
            Route::post('/category/store', [CoaController::class, 'StoreCoaCategories']);
            Route::post('/category/{id}/update', [CoaController::class, 'UpdateCoaCategories']);


            Route::get('/', [CoaController::class, 'index']);
            Route::get('/coa-saldo', [CoaController::class, 'getSaldo']);
            Route::post('/store-saldo', [CoaController::class, 'storeSaldo']);
            Route::post('/{id}/update', [CoaController::class, 'update']);
            Route::get('/getCoaCashBank', [CoaController::class, 'getCoaCashBank']);
            Route::post('/store', [CoaController::class, 'store']);
            Route::get('/get_category/{id}', [CoaController::class, 'getCode']);
        });

        // ** JURNAL UMUM
        Route::prefix('journal')->group(function () {
            Route::get('/create', [JournalController::class, 'create']);
            Route::get('/{id}/revisi', [JournalController::class, 'revisi']);
            Route::post('/{id}/revisi', [JournalController::class, 'revisiStore']);
            Route::get('/{id}/cancel', [JournalController::class, 'cancel']);
            Route::get('/revisi', [JournalController::class, 'history']);
            Route::post('/store', [JournalController::class, 'store']);
            Route::post('/{id}/edit', [JournalController::class, 'editJournal']);
            Route::get('/{id}/delete', [JournalController::class, 'deleteJournal']);
            Route::get('/general_ledger', [JournalController::class, 'general_ledger']);
            Route::get('/general_ledger/table', [JournalController::class, 'general_ledger_table']);
            Route::get('/get/ref', [JournalController::class, 'get_ref']);
        });

        //** Report  */
        Route::prefix('report')->group(function () {
            Route::get('/journal', [FinanceReportController::class, 'journal']);
            Route::get('/general_ledger', [FinanceReportController::class, 'general_ledger']);
            Route::get('/general_ledger/table', [FinanceReportController::class, 'general_ledger_table']);
            Route::get('/balance_sheet', [FinanceReportController::class, 'balance_sheet']);
            Route::get('/balance_sheet/table', [FinanceReportController::class, 'balance_sheet_table']);
            Route::get('/trial_balance', [FinanceReportController::class, 'trial_balance']);
            Route::get('/adjustment_journal', [FinanceReportController::class, 'adjustment_journal']);
            Route::get('/closing_journal', [FinanceReportController::class, 'closing_journal']);
            Route::get('/closing_trial_balance', [UomController::class, 'closing_trial_balance']);
            Route::get('/profit_loss', [FinanceReportController::class, 'profit_loss']);
            Route::get('/worksheet', [FinanceReportController::class, 'worksheet']);
            Route::get('/capital_change', [FinanceReportController::class, 'capital_change']);
            Route::get('/capital_change/table', [FinanceReportController::class, 'capital_change_table']);
        });
    });
    
    Route::get('ubah-harga', [SalesOrderController::class, 'ubahHarga']);

    Route::get('ubah-harga/{id}', [SalesOrderController::class, 'ubahHarga_get']);
    Route::get('ubah-harga-direct', [UomController::class, 'ubahHargaDirect']);
    Route::get('ubah-harga-direct/{id}', [UomController::class, 'ubahHargaDirect_get']);
    //ajax route
    Route::get('/products/cekproduct', [StockController::class, 'cekProduk']);
    Route::get('/product_sub_materials/select/{id}', [SubMaterialController::class, 'select']);
    Route::get('/product_sub_types/select/{id}', [SubTypeController::class, 'select']);
    Route::get('/product_sub_types/selectAll', [SubTypeController::class, 'selectAll']);
    Route::get('/product_select/{id}', [ProductController::class, 'selectProduct']);
    Route::get('/products/select', [ProductController::class, 'select']);
    Route::get('/products/selectAll', [ProductController::class, 'selectAll']);
    Route::get('/products/selectByWarehouse', [ProductController::class, 'selectByWarehouse']);
    Route::get('/discounts/select/{customer_id}/{product_id}', [DiscountController::class, 'select']);
    Route::get('/products/select_without', [ProductController::class, 'selectWithout']);
    Route::get('/recent_sales_order', [SalesOrderController::class, 'getRecentData']);
    Route::get('/preview_sales_order', [SalesOrderController::class, 'preview']);
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
    Route::get('/invoice/{id}/delivery_order', [SalesOrderController::class, 'printDeliveryOrder']);
    Route::get('/invoice/{id}/cash_receipt', [SalesOrderController::class, 'printCashReceipt']);
    Route::get('/direct_sales/{id}/cash_receipt', [DirectSalesController::class, 'printCashReceipt']);
    Route::post('/invoice/{id}/update_payment', [SalesOrderController::class, 'updatePaid']);
    Route::post('/invoice/{id}/cancel_payment', [SalesOrderController::class, 'cancelPaid']);
    Route::get('/sales_orders/approve/{id}', [SalesOrderController::class, 'approve']);
    Route::get('/products/selectCost/{id}', [ProductController::class, 'selectCost']);
    Route::get('/products/selectCostDecrypted/{id}', [ProductController::class, 'selectCostDecrypted']);
    Route::get('/invoice/manage_payment', [SalesOrderController::class, 'paidManagement']);
    Route::get('/send_email/{id}', [SendEmailController::class, 'index']);
    Route::get('/sales_orders/reject/{id}', [SalesOrderController::class, 'reject']);
    Route::post('/sales_order/{id}/reject_from_verification', [SalesOrderController::class, 'reject_from_verification']);
    Route::get('/sales_order/rejected', [SalesOrderController::class, 'rejected_sales_order']);
    Route::get('/customers/getTotalCredit/{id}', [CustomerController::class, 'getTotalCredit']);
    Route::get('/all_purchase_orders', [PurchaseOrderController::class, 'getPO']);
    Route::get('/file_do', [FilesController::class, 'getDO']);
    Route::get('/po/{id}/print', [PurchaseOrderController::class, 'printPO']);
    Route::get('/send_email_po/{id}', [SendEmailController::class, 'sendPo']);
    Route::get('/file_po', [FilesController::class, 'getFilePo']);
    Route::get('/notification/getAll/', [NotificationController::class, 'getAll']);
    Route::get('/read_notif/{param}/', [NotificationController::class, 'readMessage']);
    Route::post('/purchase_orders/{id}/validate', [PurchaseOrderController::class, 'validation']);
    Route::post('/purchase_orders/{id}/update_po', [PurchaseOrderController::class, 'updatePO']);
    Route::get('/purchase_orders/receiving', [PurchaseOrderController::class, 'receivingPO']);
    Route::get('/purchase_orders/manage_payment', [PurchaseOrderController::class, 'paidManagement']);
    Route::get('/purchase_orders/getTotalInstalment/{id}', [PurchaseOrderController::class, 'getTotalInstalment']);
    Route::post('/purchase_orders/{id}/update_payment', [PurchaseOrderController::class, 'updatePaid']);
    Route::post('/purchase_orders/{id}/cancel_payment', [PurchaseOrderController::class, 'cancelPaid']);
    Route::get('/purchase_orders/getDot', [PurchaseOrderController::class, 'getDot']);

    Route::get('/read_all_notif/{param}', [NotificationController::class, 'readAll']); //read all notification
    Route::get('/invoice/getTotalInstalment/{id}', [SalesOrderController::class, 'getTotalInstalment']);
    Route::get('/print_history_payment/{id}', [SalesOrderController::class, 'printHistoryPayment']);
    Route::get('/analytics', [AnalysisController::class, 'index']);
    Route::get('/salesman_chart', [AnalysisController::class, 'salesmanChart']);
    Route::get('/product_chart', [AnalysisController::class, 'productChart']);
    Route::get('/report_purchase', [ReportController::class, 'report_po']);
    Route::get('/report_purchase_safe', [ReportController::class, 'report_po_safe']);
    Route::get('/report_employee/', [ReportController::class, 'reportEmployee']);
    Route::get('/report_attendance', [ReportController::class, 'reportAttendance']);
    Route::get('/report_asset/', [ReportController::class, 'reportAsset']);
    Route::get('/report_sales/', [ReportController::class, 'index']);
    Route::get('/report_retail/', [ReportController::class, 'report_retail']);
    Route::get('/report_receivable/', [ReportController::class, 'report_receivable']);
    Route::get('/report_receivable_indirect/', [ReportController::class, 'report_receivable_indirect']);
    Route::get('/report_receivable_direct/', [ReportController::class, 'report_receivable_direct']);
    Route::get('/report_debt/', [ReportController::class, 'report_debt']);
    Route::get('/report_mutation/', [ReportController::class, 'report_mutation']);
    Route::get('/report_trade_in_return/', [ReportController::class, 'report_trade_in_return']);
    Route::get('/report_trade_sale_return/', [ReportController::class, 'report_trade_sale_return']);
    Route::get('/sales_order/selectReturn', [SalesOrderController::class, 'selectReturn']);
    Route::get('/trade_in/selectReturn', [ProductTradeInController::class, 'selectReturn']);

    // Route::get('/history_claim/', [ClaimController::class, 'historyClaim']);
    Route::get('/report_claim/', [ReportController::class, 'reportClaim']);
    Route::get('/report_secondsale/', [ReportController::class, 'reportSecondSale']);
    Route::get('/report_stock/', [ReportController::class, 'reportStock']);
    Route::get('/report_stock_trace/', [ReportController::class, 'reportStockTrace']);

    Route::get('/report_car/', [ReportController::class, 'reportCar']);
    Route::get('/report_moto/', [ReportController::class, 'reportMoto']);
    Route::get('/report_vendor/', [ReportController::class, 'reportVendor']);
    Route::get('/report_return_invoice/', [ReportController::class, 'report_return']);
    Route::get('/report_return_purchases/', [ReportController::class, 'report_return_purchase']);
    Route::get('/report_settlement/', [ReportController::class, 'report_settlement']);
    Route::get('/report_settlement_direct/', [ReportController::class, 'report_settlement_direct']);
    Route::get('/report_settlement_purchase/', [ReportController::class, 'report_settlement_purchase']);
    Route::get('/report_credit_limit/', [ReportController::class, 'report_credit_limit']);
    Route::get('/report_customer/', [ReportController::class, 'report_customer']);

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
    Route::get('claim/selectProduct', [ClaimController::class, 'selectProduct']);
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
    Route::get('retail_return_report', [ReportController::class, 'reportReturnRetail']);
    Route::get('jurnal', [AccountingController::class, 'jurnal']);
    Route::get('/sub_account/select/{id}', [AccountingController::class, 'select']);
    Route::get('/sub_type_account/select/', [AccountingController::class, 'select_type']);
    Route::get('motocycle_brand/select/{id}', [MotorController::class, 'select']);
    Route::get('district/selectAll', [DirectSalesController::class, 'select']);
    // Route::post('expenses/store', [AccountingController::class, 'storeExpenses']);
    Route::get('/retail_second_products/select', [SecondSaleController::class, 'select']);
    Route::get('/retail_second_products/cekQty/{id_product}', [SecondSaleController::class, 'cekQty']);
    Route::get('prospective_employees/create_code', [ProspectiveEmployeeController::class, 'createCode']);
    Route::get('profit_loss', [AccountingController::class, 'profit_loss']);
    Route::get('stock_c01', [StockController::class, 'stock_c01']);
    Route::get('stock_c02', [StockController::class, 'stock_c02']);
    Route::get('stock_c03', [StockController::class, 'stock_c03']);
    Route::get('stock_ss01', [StockController::class, 'stock_ss01']);
    Route::get('stock_vendor', [StockController::class, 'stock_vendor']);
    Route::get('/tradein/selectCost/', [ProductTradeInController::class, 'selectCost']);
    Route::get('trade_invoice/{id}/edit', [ProductTradeInController::class, 'editTradeInvoice']);
    Route::get('delete/trade_purchase/{id}', [ProductTradeInController::class, 'deleteTradePurchase']);
    Route::post('/trade_in/{id}/edit_superadmin', [ProductTradeInController::class, 'editSuperadmin']);
    Route::post('/second_sale/{id}/edit_superadmin', [SecondSaleController::class, 'editSuperadmin']);
    Route::get('/get_warehouses/', [ProductController::class, 'getWarehouse']);
    Route::get('/get_warehouse/', [ProductTradeInController::class, 'getWarehouse']);
    Route::post('/journal/{id}/edit_superadmin', [AccountingController::class, 'editSuperadmin']);
    Route::get('/decrypt', [LoginController::class, 'decrypt']);
    Route::get('/reset_password/{id}', [UserController::class, 'reset_password']);
    Route::get('/asset/update_status/{id}', [AssetController::class, 'update_status']);
    // Route::get('/asset/get', [AssetController::class, 'getAssets']);
    Route::get('/user_authorization', [LoginController::class, 'decrypt']);
    Route::get('/report_second_stock', [ReportController::class, 'reportSecondStock']);
    Route::get('/list_claim', [ClaimController::class, 'listClaim']);
    Route::get('/products/selectPrice/{id}', [ProductController::class, 'selectPrice']);
    Route::get('/customer/select/', [CustomerController::class, 'select']);
    Route::get('/delete/indirect_invoice/{id}', [SalesOrderController::class, 'deleteInvoice']);
    Route::get('/delete/direct_invoice/{id}', [DirectSalesController::class, 'deleteInvoice']);
    Route::get('/delete/purchase/{id}', [PurchaseOrderController::class, 'deleteInvoice']);
    Route::get('/delete/trade_sale/{id}', [SecondSaleController::class, 'deleteTradeSale']);
    Route::get('create/trade_in/{any?}', [ProductTradeInController::class, 'product_trade_in']);
    Route::get('get_max_code/account_sub', [AccountSubController::class, 'getMaxCode']);
    Route::get('get_max_code/account_sub_type', [AccountSubTypeController::class, 'getMaxCode']);
      Route::get('/customer/get_select/', [TripController::class, 'Getselect']);
    Route::get('get_cities/', [TripController::class, 'getCities']);
    Route::get('get_bank/', [TripController::class, 'getBank']);
    Route::get('purchase_home/',[App\Http\Controllers\HomeController::class, 'purchase']);
        Route::get('return_home/',[App\Http\Controllers\HomeController::class, 'return']);

    // tyre dot
    Route::prefix('tyre_dot')->group(function () {
        Route::get('/', [TyreDotController::class, 'index']);
        Route::get('/create', [TyreDotController::class, 'create']);
        Route::post('/store', [TyreDotController::class, 'store']);
        Route::get('/{id}/edit', [TyreDotController::class, 'edit']);
        Route::post('/{id}/update', [TyreDotController::class, 'update']);
        Route::delete('/{id}/delete', [TyreDotController::class, 'delete']);
        Route::post('save_data', [TyreDotController::class, 'saveData']);
        Route::get('updateweek/{id}', [TyreDotController::class, 'updateWeek']);
        Route::get('updateyear/{id}', [TyreDotController::class, 'updateYear']);
        Route::get('updateqty/{id}', [TyreDotController::class, 'updateQty']);
        Route::get('/selectDot', [TyreDotController::class, 'selectDot']);
        Route::get('/checkExceed', [TyreDotController::class, 'checkExceed']);
        Route::get('delete/{id}', [TyreDotController::class, 'deleteData']);
    });
    
    Route::prefix('trip')->group(function () {
        Route::get('/create', [TripController::class, 'create']);
        Route::get('/create/history', [TripController::class, 'history']);
        Route::post('/store', [TripController::class, 'store']);
        Route::get('/approval', [TripController::class, 'index_approval']);
        Route::get('/finance_approval', [TripController::class, 'finance_approval']);
        Route::post('/approval', [TripController::class, 'approval']);
        Route::post('/finance_approval', [TripController::class, 'financeApproval']);
        Route::get('/list', [TripController::class, 'list_trip']);
        Route::get('/completed', [TripController::class, 'trip_completed']);
        Route::get('/get-employee', [TripController::class, 'getEmployee']);
        Route::post('/completed/propose/{id}', [TripController::class, 'trip_completed_store']);
        Route::get('/completed/ga_approval', [TripController::class, 'trip_completed_approval_ga']);
        Route::get('/completed/finance_approval', [TripController::class, 'trip_completed_approval_finance']);
        Route::post('/completed/approve_by_ga/{id}', [TripController::class, 'trip_completed_approve_ga']);
        Route::get('/completed/reject_by_ga/{id}', [TripController::class, 'trip_completed_reject_ga']);
        Route::post('/completed/approve_by_finance/{id}', [TripController::class, 'trip_completed_approve_finance']);
        Route::get('/completed/reject_by_finance/{id}', [TripController::class, 'trip_completed_reject_finance']);
        Route::get('/completed/print/{id}', [TripController::class, 'trip_completed_print']);
        Route::get('/completed/list', [TripController::class, 'trip_completed_list']);
        Route::get('/report', [ReportController::class, 'report_trip']);
        Route::get('/delete/{id}',[TripController::class, 'destroy']);
    });
      // Route for claim
    Route::prefix('claim')->group(function () {
        Route::get('/', [ClaimController::class, 'index']);
        Route::get('/settlement', [ClaimController::class, 'settlement']);
        Route::post('/settlement/{id}/update', [ClaimController::class, 'updateSettlement']);
        ROute::get('{id}/create/prior', [ClaimController::class, 'create']);
        Route::get('/create/initial', [ClaimController::class, 'createInitialClaim']);
        Route::post('/store/initial', [ClaimController::class, 'storeInitialClaim']);
        Route::post('{id}/update/initial', [ClaimController::class, 'updateInitialClaim']);
        Route::post('{id}/store/prior', [ClaimController::class, 'storeClaimPrior']);
        Route::get('/final/check', [ClaimController::class, 'checkFinalClaim']);
        Route::get('/{id}/create/final', [ClaimController::class, 'createFinalClaim']);
        Route::post('/{id}/store/final', [ClaimController::class, 'storeFinalClaim']);
    });
    Route::get('/history_claim', [ClaimController::class, 'historyClaim']);

    Route::prefix('expenses')->group(function () {
        Route::get('/create', [AccountingController::class, 'createExpenses']);
        Route::post('/store', [AccountingController::class, 'store_expense']);
        Route::post('/{id}/edit_expenses', [AccountingController::class, 'update_expense']);
        Route::get('/delete/{id}', [AccountingController::class, 'delete_expense']);
    });

    Route::prefix('depreciation')->group(function () {
        Route::get('/', [AccountingController::class, 'depreciation']);
    });

    Route::prefix('document_renewal')->group(function () {
        Route::get('/', [DocumentUpdateController::class, 'index']);
        Route::post('/', [DocumentUpdateController::class, 'store']);
        Route::put('/{id}', [DocumentUpdateController::class, 'update']);
        Route::delete('/{id}', [DocumentUpdateController::class, 'destroy']);
        Route::post('/{id}/renew', [DocumentUpdateController::class, 'renew']);
    });

    //** route for program */
    Route::prefix('program')->group(function () {
        Route::get('/', [ProgramController::class, 'index']);
        Route::get('/create', [ProgramController::class, 'create']);
        Route::post('/store', [ProgramController::class, 'store']);
        Route::get('/{id}/edit', [ProgramController::class, 'edit']);
        Route::post('/{id}/update', [ProgramController::class, 'update']);
        Route::delete('/{id}/delete', [ProgramController::class, 'delete']);
    });


    //** route for banners */
    Route::prefix('banners')->group(function () {
        Route::get('/', [BannerController::class, 'index']);
        Route::get('/create', [BannerController::class, 'create']);
        Route::post('/store', [BannerController::class, 'store']);
        Route::get('/edit/{id}', [BannerController::class, 'edit']);
        Route::post('/update/{id}', [BannerController::class, 'update']);
        Route::delete('/delete/{id}', [BannerController::class, 'delete']);
    });

    //** route for leave */
    Route::prefix('leave')->group(function () {
        Route::get('/', [VacationController::class, 'index']);
        Route::get('/create', [VacationController::class, 'create']);
        Route::post('/store', [VacationController::class, 'store']);
        Route::get('/history', [VacationController::class, 'history']);
        Route::get('/all_data', [VacationController::class, 'allData']);
        Route::get('/approve/{id}', [VacationController::class, 'approve']);
        Route::post('/reject/{id}', [VacationController::class, 'reject']);
        Route::get('/{id}/edit', [VacationController::class, 'edit']);
        Route::post('/{id}/update', [VacationController::class, 'update']);
        Route::get('/{id}/delete', [VacationController::class, 'deleteVacation']);
        Route::get('/get-employee', [VacationController::class, 'getEmployee']);

    });
    
     //** route for addtional leave */
    Route::prefix('additional_leave')->group(function () {
        Route::get('/', [VacationController::class, 'additional_index']);
        Route::get('/create_additional_vacation', [VacationController::class, 'create_additional']);
        Route::post('/store_additional_vacation', [VacationController::class, 'store_additional']);
        Route::get('/approve_additional_vacation', [VacationController::class, 'approve_additional']);
        Route::post('/approve_additional_vacation/{id}', [VacationController::class, 'approveAdditional']);
        Route::get('/reject_additional_vacation/{id}', [VacationController::class, 'reject_additional'])->name('reject.additional.vacation');
        Route::get('/history_additional_vacation', [VacationController::class, 'history_additional']);
    });
    
     //** route for action plans */
    Route::prefix('action_plans')->group(function () {
        Route::get('/approve', [ActionPlansController::class, 'approve_plans']);
        Route::get('/history', [ActionPlansController::class, 'approve_plans']);
        Route::post('/approve/{id}', [ActionPlansController::class, 'approve']);
        Route::get('/reject/{id}', [ActionPlansController::class, 'reject'])->name('reject.action.plans');
        Route::get('/history', [ActionPlansController::class, 'history']);
    });

    //** route for attendances */
    Route::prefix('attendances')->group(function () {
        Route::get('/', [AttendancesController::class, 'index'])->name('attendances.index');
        Route::get('/get_user', [AttendancesController::class, 'getuser']);
    });

    Route::prefix('retail')->group(function () {
        Route::get('/', [DirectSalesController::class, 'index']);
        Route::get('/create', [DirectSalesController::class, 'create']);
        Route::post('/store', [DirectSalesController::class, 'store']);
        Route::get('/approval', [DirectSalesController::class, 'approval']);
        Route::get('/approve/{id}', [DirectSalesController::class, 'approve']);
        Route::get('/reject/{id}', [DirectSalesController::class, 'reject']);
        Route::get('/credit', [DirectSalesController::class, 'credit']);
        Route::get('/print_invoice/{id}', [DirectSalesController::class, 'print_invoice']);
        Route::get('/print_do/{id}', [DirectSalesController::class, 'print_do']);
        Route::get('/send_mail/{id}', [SendEmailController::class, 'send_mail_retail']);
        Route::get('/mark_as_paid/{id}', [DirectSalesController::class, 'mark_as_paid']);
        Route::get('/selectProductAll', [DirectSalesController::class, 'selectProductAll']);
        Route::post('/{id}/update_retail', [DirectSalesController::class, 'update_retail']);
        Route::get('/search', [DirectSalesController::class, 'search']);
        Route::get('/selectById', [DirectSalesController::class, 'selectById']);
        Route::get('/selectWarehouse', [DirectSalesController::class, 'selectWarehouse']);
        Route::get('/print_struk/{id}', [DirectSalesController::class, 'PrintStruk']);
        Route::get('/selectReturn', [DirectSalesController::class, 'selectReturn']);
        Route::get('/manage_payment', [DirectSalesController::class, 'paidManagement']);
        Route::get('/getTotalInstalment/{id}', [DirectSalesController::class, 'getTotalInstalment']);
        Route::post('/{id}/update_payment', [DirectSalesController::class, 'updatePaid']);
        Route::post('/{id}/cancel_payment', [DirectSalesController::class, 'cancelPaid']);
        Route::get('/getQtyDetail', [DirectSalesController::class, 'getQtyDetail']);
        Route::get('/getDot', [DirectSalesController::class, 'getDot']);
        Route::get('/rejected', [DirectSalesController::class, 'rejected']);
        Route::get('/modal/endpoint',[DirectSalesController::class, 'retailModalEndpoint']);
    });

    Route::prefix('cars_type')->group(function () {
        Route::get('/', [CarController::class, 'index_type']);
        Route::post('/store', [CarController::class, 'store_type']);
        Route::post('/update_type/{id}', [CarController::class, 'update_type']);
        Route::delete('/delete/{id}', [CarController::class, 'delete_type']);
    });

    Route::prefix('return')->group(function () {
        Route::get('/', [ReturnController::class, 'index']);
        Route::get('/{id}', [ReturnController::class, 'create'])->where('id', '[0-9]+'); // Constraint untuk memastikan {id} adalah integer
        Route::get('/approval', [ReturnController::class, 'approval_indirect']);
        Route::get('/receiving', [ReturnController::class, 'receiving_indirect']);
        Route::post('/store', [ReturnController::class, 'store']);
        Route::post('/{id}/update_return', [ReturnController::class, 'update_return']);
        Route::post('/{id}/approve_return', [ReturnController::class, 'approve_indirect']);
        Route::post('/{id}/receive_return', [ReturnController::class, 'receive_indirect']);
        Route::get('/{id}/print', [ReturnController::class, 'print_return']);
        Route::get('/{id}/send_email', [SendEmailController::class, 'send_return']);
        Route::get('/delete/return_indirect/{id}', [ReturnController::class, 'delete_return_indirect']);
    });

    Route::get('/trade_in/selectReturn/', [ProductTradeInController::class, 'selectReturn']);
    Route::get('/trade_in/getQtyDetail/', [ProductTradeInController::class, 'getQtyDetail']);

    Route::prefix('return_trade_in')->group(function () {
        Route::get('/', [ReturnController::class, 'indexTradeIn']);
        Route::get('/{id}', [ReturnController::class, 'createTradeIn']);
        Route::post('/store', [ReturnController::class, 'storeTradeIn']);
        Route::post('/{id}/update_return', [ReturnController::class, 'updateTradeIn']);
        Route::get('/{id}/print', [ReturnController::class, 'printTradeIn']);
        Route::get('/{id}/send_email', [SendEmailController::class, 'emailTradeIn']);
        Route::get('/delete/return_trade_in/{id}', [ReturnController::class, 'delete_return_trade_in']);
    });

    Route::get('/return_trade_in_sale/selectReturn/', [SecondSaleController::class, 'selectReturn']);
    Route::get('/return_trade_in_sale/getQtyDetail/', [SecondSaleController::class, 'getQtyDetail']);
    Route::prefix('return_trade_in_sale')->group(function () {
        Route::get('/', [ReturnController::class, 'indexTradeInSale']);
        Route::get('/{id}', [ReturnController::class, 'createTradeInSale']);
        Route::post('/store', [ReturnController::class, 'storeTradeInSale']);
        Route::post('/{id}/update_return', [ReturnController::class, 'updateTradeInSale']);
        Route::get('/{id}/print', [ReturnController::class, 'printTradeInSale']);
        Route::get('/{id}/send_email', [SendEmailController::class, 'emailTradeInSale']);
        Route::get('/delete/return_trade_in/{id}', [ReturnController::class, 'delete_return_trade_in_sale']);
    });
    Route::prefix('return_purchase')->group(function () {
        Route::get('/', [ReturnController::class, 'index_purchase']);
        Route::get('/{id}', [ReturnController::class, 'create_purchase']);
        Route::post('/store_purchase', [ReturnController::class, 'store_purchase']);
        Route::post('/{id}/update_return_purchase', [ReturnController::class, 'update_return_purchase']);
        Route::get('/{id}/print', [ReturnController::class, 'print_return_purchase']);
        Route::get('/delete/return_purchase/{id}', [ReturnController::class, 'delete_return_purchase']);
    });

    Route::prefix('return_retail')->group(function () {
        Route::get('/', [ReturnController::class, 'index_retail']);
        Route::get('/{id}', [ReturnController::class, 'create_retail'])->where('id', '[0-9]+'); // Constraint untuk memastikan {id} adalah integer
        Route::get('/approval', [ReturnController::class, 'approval_retail']);
        Route::get('/receiving', [ReturnController::class, 'receiving_retail']);
        Route::post('/store_retail', [ReturnController::class, 'store_retail']);
        Route::post('/{id}/update_return_retail', [ReturnController::class, 'update_return_retail']);
        Route::post('/{id}/approve_return_retail', [ReturnController::class, 'approve_retail']);
        Route::post('/{id}/receive_return_retail', [ReturnController::class, 'receive_retail']);
        Route::get('/{id}/print', [ReturnController::class, 'print_return_retail']);
        Route::get('/delete/return_direct/{id}', [ReturnController::class, 'delete_return_direct']);
    });
    
   Route::prefix('material-promotion')->group(function () {
        Route::get('/', [ItemPromotionController::class, 'index']);
        Route::post('/store', [ItemPromotionController::class, 'store']);
        Route::post('/{id}/update', [ItemPromotionController::class, 'update']);
        Route::delete('/{id}/delete', [ItemPromotionController::class, 'delete']);

        //Stock
        Route::get('/stock', [ItemPromotionController::class, 'index_stock']);
        Route::post('/{id}/update_stock', [ItemPromotionController::class, 'update_stock']);

        //Transaction
        Route::get('/transaction/create', [ItemPromotionController::class, 'create_transaction']);
        Route::get('/select', [ItemPromotionController::class, 'selectItem']);
        Route::get('/selectPrice', [ItemPromotionController::class, 'selectPrice']);
        Route::get('/cekQty/{product_id}', [ItemPromotionController::class, 'cekQty']);
        Route::post('/transaction/store', [ItemPromotionController::class, 'store_transaction']);
        Route::get('/transaction', [ItemPromotionController::class, 'index_transaction']);
        Route::get('/transaction/approval', [ItemPromotionController::class, 'approval_transaction']);
        Route::post('/transaction/{id}/approve', [ItemPromotionController::class, 'approve_transaction']);
        Route::post('/transaction/{id}/reject', [ItemPromotionController::class, 'reject_transaction']);
        Route::get('/transaction/{id}/delivery_order', [ItemPromotionController::class, 'printDeliveryOrder']);
        Route::get('/transaction/{id}/delivery_order_struk', [ItemPromotionController::class, 'printDeliveryOrderStruk']);
        Route::get('/transaction/{id}/delete', [ItemPromotionController::class, 'delete_transaction']);
        Route::post('/transaction/{id}/update', [ItemPromotionController::class, 'update_transaction']);
        Route::get('/transaction/preview', [ItemPromotionController::class, 'preview_transaction']);
        Route::get('/transaction/create_by_invoice/{id?}', [ItemPromotionController::class, 'create_transaction']);

        //Purchase
        Route::get('/purchase/create', [ItemPromotionController::class, 'create_purchase']);
        Route::post('/purchase/store', [ItemPromotionController::class, 'store_purchase']);
        Route::get('/selectByItem', [ItemPromotionController::class, 'selectByItem']);
        Route::get('/purchase', [ItemPromotionController::class, 'index_purchase']);
        Route::get('/purchase/approval', [ItemPromotionController::class, 'approval_purchase']);
        Route::post('/purchase/{id}/approve', [ItemPromotionController::class, 'approve_purchase']);
        Route::post('/purchase/{id}/reject', [ItemPromotionController::class, 'reject_purchase']);
        Route::get('/purchase/print/{id}', [ItemPromotionController::class, 'print_purchase']);
        Route::get('/purchase/preview', [ItemPromotionController::class, 'preview_purchase']);

        //Mutation
        Route::get('/mutation/create', [ItemPromotionController::class, 'create_mutation']);
        Route::get('/mutation', [ItemPromotionController::class, 'index_mutation']);
        Route::post('/mutation/store', [ItemPromotionController::class, 'store_mutation']);
        Route::get('/mutation/approval', [ItemPromotionController::class, 'approval_mutation']);
        Route::post('/mutation/{id}/approve', [ItemPromotionController::class, 'approve_mutation']);
        Route::get('/mutation/{id}/reject', [ItemPromotionController::class, 'reject_mutation']);
        Route::get('/mutation/print/{id}', [ItemPromotionController::class, 'print_mutation']);

        //Vendor
        Route::get('/vendor', [ItemPromotionController::class, 'index_supplier']);
        Route::post('/vendor/store', [ItemPromotionController::class, 'store_supplier']);
        Route::post('/vendor/{id}/update', [ItemPromotionController::class, 'update_supplier']);
        Route::delete('/vendor/{id}/delete', [ItemPromotionController::class, 'delete_supplier']);

        //Return
        Route::get('/return/{id}/create', [ReturnController::class, 'create_item_promotion']);
        Route::get('/return', [ReturnController::class, 'index_item_promotion']);
        Route::post('/return/store', [ReturnController::class, 'store_item_promotion']);
        Route::post('/return/{id}/delete', [ReturnController::class, 'delete_item_promotion']);
        Route::get('/return/{id}/print', [ReturnController::class, 'print_item_promotion']);
        Route::get('/selectReturn', [ItemPromotionController::class, 'selectItemReturn']);
        Route::get('/selectQtyReturn', [ItemPromotionController::class, 'selectQtyReturn']);
        Route::get('/purchase/return/{id}/create', [ReturnController::class, 'create_purchase_item_promotion']);
        Route::get('/purchase/return', [ReturnController::class, 'index_purchase_item_promotion']);
        Route::post('/purchase/return/store', [ReturnController::class, 'store_purchase_item_promotion']);
        Route::post('/purchase/return/{id}/delete', [ReturnController::class, 'delete_purchase_item_promotion']);
        Route::get('/selectPurchaseReturn', [ItemPromotionController::class, 'selectPurchaseItemReturn']);
        Route::get('/selectPurchaseQtyReturn', [ItemPromotionController::class, 'selectPurchaseQtyReturn']);

        //Report
        Route::get('/transaction/report', [ReportController::class, 'report_promotion_transaction']);
        Route::get('/purchase/report', [ReportController::class, 'report_promotion_purchase']);
        Route::get('/mutation/report', [ReportController::class, 'report_promotion_mutation']);
        Route::get('/stock/report', [ReportController::class, 'report_promotion_stock']);
        Route::get('/return/report', [ReportController::class, 'report_promotion_return']);
        Route::get('/purchase/return/report', [ReportController::class, 'report_promotion_purchase_return']);
    });

    Route::prefix('stock_mutation')->group(function () {
        Route::get('/', [StockMutationController::class, 'index']);
        Route::get('/create', [StockMutationController::class, 'create']);
        Route::get('/second_create', [StockMutationController::class, 'second_create']);
        Route::get('/approval', [StockMutationController::class, 'approval']);
        Route::get('/receiving', [StockMutationController::class, 'receiving']);
        Route::post('/{id}/approve_mutation', [StockMutationController::class, 'approve_mutation']);
        Route::get('/{id}/reject_mutation', [StockMutationController::class, 'reject_mutation']);
        Route::post('/{id}/receive_mutation', [StockMutationController::class, 'receive_mutation']);
        Route::get('/select', [StockMutationController::class, 'select']);
        Route::get('/getQtyDetail', [StockMutationController::class, 'getQtyDetail']);
        Route::get('/checkMaterial', [StockMutationController::class, 'checkMaterial']);
         Route::get('/selectSecond', [StockMutationController::class, 'selectSecond']);
        Route::get('/getSecondProductQty', [StockMutationController::class, 'getSecondProductQty']);
        Route::post('/store', [StockMutationController::class, 'store']);
        Route::post('/second_store', [StockMutationController::class, 'second_store']);
        Route::post('/{id}/update_mutation', [StockMutationController::class, 'update_mutation']);
        Route::get('/{id}/print_do', [StockMutationController::class, 'print_do']);
        Route::get('/delete/{id}', [StockMutationController::class, 'delete_mutation']);
    });

    Route::prefix('daily_activity')->group(function () {
        Route::get('/', [DailyActivityController::class, 'index']);
        Route::post('/store', [DailyActivityController::class, 'store']);
        Route::get('/report', [ReportController::class, 'report_daily']);
    });

    Route::prefix('value_added_tax')->group(function () {
        Route::get('/', [ValueAddedTaxController::class, 'index']);
        Route::post('/{id}/update', [ValueAddedTaxController::class, 'update']);
    });

    Route::prefix('national_day')->group(function () {
        Route::get('/', [NationalDayController::class, 'index']);
        Route::post('/store', [NationalDayController::class, 'store']);
        Route::post('{id}/update', [NationalDayController::class, 'update']);
        Route::delete('{id}/delete', [NationalDayController::class, 'destroy']);
    });

    Route::prefix('delivery_history')->group(function () {
        Route::get('/', [DeliveryHistoriesController::class, 'index']);
        Route::post('/{id}/store', [DeliveryHistoriesController::class, 'store']);
        Route::post('/update_dot_stock', [DeliveryHistoriesController::class, 'update_dot_stock']);
    });
    Route::get('/preview_purchase_order', [PurchaseOrderController::class, 'preview']);

    Route::get('uom/updateInline/{id}', [UomController::class, 'updateInline']);
    Route::get('product_uoms', [UomController::class, 'index']);


    //! Route for Superadmin
    Route::post('/purchase_orders/{id}/manage', [PurchaseOrderController::class, 'manage']);
    Route::post('/purchase_orders/{id}/reject', [PurchaseOrderController::class, 'reject']);

    Route::resource('/customer_categories', CustomerCategoriesController::class);
    Route::resource('/customer_areas', CustomerAreasController::class);
    Route::resource('/warehouses', WarehouseController::class);
    Route::resource('/supliers', SuppliersController::class);
    Route::resource('/jobs', JobController::class);
    Route::resource('/roles', RoleController::class);
    Route::resource('/users', UserController::class);
    Route::resource('/product_materials', MaterialController::class);
    Route::resource('/product_sub_materials', SubMaterialController::class);
    Route::resource('/product_uoms', UomController::class)->except('updateInline');
    Route::resource('/product_sub_types', SubTypeController::class);
    Route::resource('/products', ProductController::class);
    Route::resource('/second_product', SecondProductController::class);
    Route::resource('/trade_in', ProductTradeInController::class);

    Route::resource('/account', AccountController::class);
    Route::resource('/account_sub', AccountSubController::class);
    Route::resource('/account_sub_type', AccountSubTypeController::class);

    Route::resource('/customers', CustomerController::class)->except('approval');
    Route::get('customer/approval', [CustomerController::class, 'approval']);
    Route::post('customer/{id}/approve', [CustomerController::class, 'approve']);

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
    Route::resource('/motorcycle', MotorController::class);
    Route::resource('/cars', CarController::class);
    Route::resource('/retail_second_products', SecondSaleController::class);
    Route::resource('/prospective_employees', ProspectiveEmployeeController::class, ['except' => ['show', 'store_form']]);
    Route::resource('/asset_category', AssetCategoryController::class);
    Route::resource('/employee', EmployeeController::class);
    Route::get('/authorization', [UserController::class, 'authorization']);
    Route::post('/update_authorization_icon/{id}', [UserController::class, 'edit_icon_authorization']);
    Route::post('/update_authorization/{id}', [UserController::class, 'edit_authorization']);
    Route::post('/change_authorization/{id}', [UserController::class, 'change_authorization']);
});

Route::group(['middleware' => 'guest'], function () {

    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate']);
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
