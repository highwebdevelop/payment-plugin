<?php

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


Route::prefix('config')->group(function () {
    Route::get('/countries', 'ConfigurationController@getCountries');
});

Route::prefix('subscription')->group(function () {
    Route::get('/status', [\Payment\System\App\Http\Controllers\SubscriptionController::class,'status']);
    Route::post('/subscribe', [\Payment\System\App\Http\Controllers\SubscriptionController::class,'subscribe']);
    Route::post('/invoice', [\Payment\System\App\Http\Controllers\SubscriptionController::class,'invoice']);
    Route::post('/trial', [\Payment\System\App\Http\Controllers\SubscriptionController::class,'getTrial']);
    Route::post('/subscribe-inner', [\Payment\System\App\Http\Controllers\SubscriptionController::class,'innerSubscribe']);
});

Route::prefix('/plans')->group(function() {
    Route::get('/all', [\Payment\System\App\Http\Controllers\PlansController::class,'all']);
});

