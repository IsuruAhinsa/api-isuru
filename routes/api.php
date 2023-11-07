<?php

use App\Http\Controllers\AttributeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LocationDataController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\ProductAttributeValueController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductPhotoController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SalesManagerController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('login', [AuthController::class, 'login']);

Route::get('/store-countries', [LocationDataController::class, 'storeCountries']);

Route::group(['middleware' => ['auth:sanctum', 'auth:admin']], static function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::apiResource('category', CategoryController::class)->except(['create', 'edit']);

    Route::apiResource('sub-category', SubCategoryController::class)->except(['create', 'edit']);

    Route::apiResource('brand', BrandController::class)->except(['create', 'edit']);
    Route::get('get-brands-list', [BrandController::class, 'getBrandsList']);

    Route::get('provinces', [LocationDataController::class, 'getProvinces']);
    Route::get('districts/{province_id}', [LocationDataController::class, 'getDistrictsByProvinceId']);
    Route::get('cities/{district_id}', [LocationDataController::class, 'getCitiesByDistrictId']);
    Route::get('get-countries-list', [LocationDataController::class, 'getCountriesList']);

    Route::apiResource('supplier', SupplierController::class)->except(['create', 'edit']);
    Route::get('get-suppliers-list', [SupplierController::class, 'getSuppliersList']);

    Route::apiResource('attribute', AttributeController::class)->except(['create', 'show', 'edit']);
    Route::get('get-attribute-list', [AttributeController::class, 'getAttributeListWithValues']);
    Route::apiResource('attribute.values', ProductAttributeValueController::class)->except(['index', 'create', 'show', 'edit', 'update']);

    Route::apiResource('products', ProductController::class)->except(['create', 'edit']);
    Route::apiResource('products.photos', ProductPhotoController::class)->except(['create', 'edit']);

    Route::apiResource('shop', ShopController::class)->except(['create', 'edit']);
    Route::get('get-shops-list', [ShopController::class, 'getShopsList']);

    Route::apiResource('sales-managers', SalesManagerController::class)->except(['create', 'edit']);
});

Route::group(['middleware' => 'auth:admin,sales_manager'], function () {
    Route::get('get-categories-list', [CategoryController::class, 'getCategoriesList']);
    Route::get('get-sub-categories-list/{category}', [SubCategoryController::class, 'getSubCategoriesList']);
    Route::apiResource('products', ProductController::class)->only(['index', 'show']);
    Route::apiResource('customers', CustomerController::class)->except(['create', 'edit']);
    Route::apiResource('orders', OrderController::class)->except(['create', 'edit']);
    Route::get('get-payment-methods', [PaymentMethodController::class, 'index']);
    Route::get('get-product-list-for-barcode', [ProductController::class, 'getProductListForBarcode']);
    Route::get('get-product-columns', [ProductController::class, 'getProductColumns']);
    Route::get('get-reports', [ReportController::class, 'index']);
});
