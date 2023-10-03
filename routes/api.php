<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LocationDataController;
use App\Http\Controllers\ProductAttributeController;
use App\Http\Controllers\ProductAttributeValueController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\SupplierController;
use Illuminate\Http\Request;
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

Route::get('/get-countries', [LocationDataController::class, 'storeCountries']);

Route::group(['middleware' => 'auth:sanctum'], static function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::apiResource('category', CategoryController::class)->except(['create', 'edit']);
    Route::get('get-categories-list', [CategoryController::class, 'getCategoriesList']);

    Route::apiResource('sub-category', SubCategoryController::class)->except(['create', 'edit']);
    Route::get('get-sub-categories-list/{category}', [SubCategoryController::class, 'getSubCategoriesList']);

    Route::apiResource('brand', BrandController::class)->except(['create', 'edit']);
    Route::get('get-brands-list', [BrandController::class, 'getBrandsList']);

    Route::get('provinces', [LocationDataController::class, 'getProvinces']);
    Route::get('districts/{province_id}', [LocationDataController::class, 'getDistrictsByProvinceId']);
    Route::get('cities/{district_id}', [LocationDataController::class, 'getCitiesByDistrictId']);
    Route::get('get-countries-list', [LocationDataController::class, 'getCountriesList']);

    Route::apiResource('supplier', SupplierController::class)->except(['create', 'edit']);
    Route::get('get-suppliers-list', [SupplierController::class, 'getSuppliersList']);

    Route::apiResource('product-attribute', ProductAttributeController::class)->except(['create', 'show', 'edit']);
    Route::get('get-product-attribute-list', [ProductAttributeController::class, 'getAttributeListWithValues']);
    Route::apiResource('product-attribute.values', ProductAttributeValueController::class)->except(['index', 'create', 'show', 'edit', 'update']);
});
