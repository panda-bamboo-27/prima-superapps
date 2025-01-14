<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Vendor;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\ItemCategoryController;

Route::get('user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('login', [AuthController::class,'authenticate'] );
Route::post('logout', [AuthController::class,'logout'] )->middleware('auth:sanctum');

Route::apiResource('vendors', VendorController::class)->middleware('auth:sanctum');
Route::put('vendors/{vendorId}/restore',[VendorController::class,'restore'])->middleware('auth:sanctum');

Route::apiResource('item_categories', ItemCategoryController::class)->middleware('auth:sanctum');
Route::put('item_categories/{itemCategoryId}/restore',[ItemCategoryController::class,'restore'])->middleware('auth:sanctum');


