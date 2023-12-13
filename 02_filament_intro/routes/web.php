<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::resource('customer', App\Http\Controllers\CustomerController::class);

Route::resource('order', App\Http\Controllers\OrderController::class);

Route::resource('line-item', App\Http\Controllers\LineItemController::class);
