<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UserController;
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

Route::middleware('auth.api')->get('/users', [UserController::class, 'apiUsers'])->name('api.users');
Route::get('/department', [CategoryController::class, 'departmentListApi'])->name('api.department.index');
Route::post('/department/store', [CategoryController::class, 'departmentStoreApi'])->name('api.department.store');
Route::get('/designation', [CategoryController::class, 'designationListApi'])->name('api.designation.index');
Route::post('/designation/store', [CategoryController::class, 'designationStoreApi'])->name('api.designation.store');
Route::get('/producttype', [CategoryController::class, 'productTypeListApi'])->name('api.producttype.index');
Route::post('/producttype/store', [CategoryController::class, 'productTypeStoreApi'])->name('api.producttype.store');
Route::get('/supplier', [CategoryController::class, 'supplierListApi'])->name('api.supplier.index');
Route::post('/supplier/store', [CategoryController::class, 'supplierStoreApi'])->name('api.supplier.store');
Route::get('/brand', [CategoryController::class, 'brandListApi'])->name('api.brand.index');
Route::post('/brand/store', [CategoryController::class, 'brandStoreApi'])->name('api.brand.store');
Route::get('/status', [CategoryController::class, 'statusListApi'])->name('api.status.index');
Route::post('/status/store', [CategoryController::class, 'statusStoreApi'])->name('api.status.store');
Route::get('/size_mesurment', [CategoryController::class, 'sizeMesurmentListApi'])->name('api.size.index');
Route::post('/size_mesurment/store', [CategoryController::class, 'sizeMesurmentStoreApi'])->name('api.size.store');
Route::get('/color', [CategoryController::class, 'colorListApi'])->name('api.color.index');
Route::post('/color/store', [CategoryController::class, 'colorStoreApi'])->name('api.color.store');
Route::get('/company', [CategoryController::class, 'companyListApi'])->name('api.company.index');
Route::post('/company/store', [CategoryController::class, 'companyStoreApi'])->name('api.company.store');
Route::get('/store', [StoreController::class, 'storeListApi'])->name('api.store.index');
Route::post('/store/store', [StoreController::class, 'storeStoreApi'])->name('api.store.store');