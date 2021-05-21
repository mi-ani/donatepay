<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::name('fill-balance.')->group(function () {
    Route::post('/fill/xyz', [\App\Http\Controllers\BalanceFillController::class, 'xyzPayment'])->name('by-xyz');
    Route::post('/fill/old', [\App\Http\Controllers\BalanceFillController::class, 'oldPayment'])->name('by-old');
});
