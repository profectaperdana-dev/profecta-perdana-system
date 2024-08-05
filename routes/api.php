<?php

use App\Http\Controllers\Cms\AreaController;
use App\Http\Controllers\Cms\ContactController;
use App\Http\Controllers\Cms\FaqController;
use App\Http\Controllers\Cms\GalleryController;
use App\Http\Controllers\Cms\AboutController;
use App\Http\Controllers\Cms\BlogController;
use App\Http\Controllers\Cms\ProductController;
use App\Http\Controllers\Cms\TeamController;
use App\Http\Controllers\Cms\PortfolioController;
use App\Http\Controllers\Cms\HomePageController;
use App\Http\Controllers\Api\Cms\JobVacationController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/getfaq', [FaqController::class, 'api_getfaq']);
Route::prefix('/getgallery')->group(function () {
    Route::get('/category', [GalleryController::class, 'api_getcategory']);
    Route::get('/', [GalleryController::class, 'api_getgallery']);
    Route::get('/{id}/filterbycategory', [GalleryController::class, 'api_filterbycategory']);
});
Route::prefix('/getcontact')->group(function () {
    Route::get('/', [ContactController::class, 'api_getcontact']);
    Route::get('/{id}/filterbyarea', [ContactController::class, 'api_filterbyarea']);
    Route::get('/areaandphone', [ContactController::class, 'api_areaandphone']);
});
Route::prefix('/getarea')->group(function () {
    Route::get('/', [AreaController::class, 'api_getarea']);
});
Route::prefix('/getteam')->group(function () {
    Route::get('/', [TeamController::class, 'api_getteam']);
});
Route::prefix('/getproduct')->group(function () {
    Route::get('/', [ProductController::class, 'ApiGetProduct']);
    Route::get('/submaterial', [ProductController::class, 'api_getsubmaterial']);
    Route::get('/{id}/filterbysubmaterial', [ProductController::class, 'api_filterbysubmaterial']);
    Route::get('/{text}/search', [ProductController::class, 'api_search']);
});
Route::prefix('/getabout')->group(function () {
    Route::get('/', [AboutController::class, 'api_getabout']);
});
Route::prefix('/getportfolio')->group(function () {
    Route::get('/', [PortfolioController::class, 'api_getportfolio']);
});
Route::prefix('/getblog')->group(function () {
    Route::get('/', [BlogController::class, 'api_getblog']);
    Route::get('/{slug}/read', [BlogController::class, 'api_read']);
    Route::get('/recent', [BlogController::class, 'api_recent']);
    Route::get('/categories', [BlogController::class, 'api_categories']);
    Route::get('/{category_id}/filterbycategory', [BlogController::class, 'api_filterbycategory']);
    Route::get('/{text}/search', [BlogController::class, 'api_search']);
});
Route::prefix('/gethomepage')->group(function () {
    Route::get('/', [HomePageController::class, 'api_gethomepage']);
});

// Route::get('/banners', [App\Http\Controllers\Api\BannerController::class, 'index']);

Route::prefix('job_vacancies')->group(function () {
    Route::get('/', [JobVacationController::class, 'index']);
    Route::get('/{id}', [JobVacationController::class, 'getVacancies']);
    Route::get('/{slug}/question', [JobVacationController::class, 'getQuestion']);
    Route::post('/', [JobVacationController::class, 'store']);
});
