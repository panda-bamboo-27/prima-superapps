<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Vendor;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\VendorController;

Route::get('user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('login', [AuthController::class,'authenticate'] );
Route::post('logout', [AuthController::class,'logout'] )->middleware('auth:sanctum');

Route::apiResource('vendors', VendorController::class)->middleware('auth:sanctum');


Route::get('users', function (Request $request) {
    return Vendor::orderBy('created_at','desc')->cursorPaginate(10);
    // return $request->user();
})->middleware('auth:sanctum');

