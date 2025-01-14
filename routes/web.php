<?php

use Illuminate\Support\Facades\Route;
use App\Http\Resources\Vendor as VendorResource;
use App\Models\Vendor;
use App\Http\Controllers\VendorController;

Route::get('/', function () {
    return view('welcome');
});
