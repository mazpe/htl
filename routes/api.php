<?php

use Illuminate\Http\Request;

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

Route::post('register', 'API\RegisterController@register');

Route::middleware('auth:api')->group( function () {
    Route::get('vehicles/{id}/keys', 'API\VehicleController@keys');
    Route::resource('vehicles', 'API\VehicleController');
    Route::resource('keys', 'API\KeyController');
    Route::resource('technicians', 'API\TechnicianController');
    Route::resource('orders', 'API\OrderController');
});
