<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LocationDataController;
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

Route::group(['middleware' => 'auth:sanctum'], static function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::apiResource('category', CategoryController::class);
    Route::get('categories/list', [CategoryController::class, 'getCategoriesList']);
    Route::apiResource('sub-category', SubCategoryController::class);
    Route::apiResource('brand', BrandController::class);
    Route::get('provinces', [LocationDataController::class, 'getProvinces']);
    Route::get('districts/{province_id}', [LocationDataController::class, 'getDistrictsByProvinceId']);
    Route::get('cities/{district_id}', [LocationDataController::class, 'getCitiesByDistrictId']);
    Route::apiResource('supplier', SupplierController::class);
});
