<?php

use App\Http\Controllers\MainController;
use App\Http\Controllers\FakePaymentSystem\XyzPaymentController;
use App\Http\Controllers\FakePaymentSystem\OldPaymentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [MainController::class, 'index']);

Route::name('donate.')->group(function () {
    Route::get('/donate/{id}', [MainController::class, 'create'])->name('create');
    Route::post('/donate', [MainController::class, 'store'])->name('store');
});

Route::prefix('xyz-payment.ru')->name('xyz.')->group(function () {
    Route::get('/pay', [XyzPaymentController::class, 'create'])->name('create');
    Route::post('/pay', [XyzPaymentController::class, 'store'])->name('store');
});


Route::prefix('old-payment.ru')->name('old.')->group(function () {
    Route::get('/api/create', [OldPaymentController::class, 'create'])->name('create');
    Route::post('/api/create', [OldPaymentController::class, 'store'])->name('store');
});
