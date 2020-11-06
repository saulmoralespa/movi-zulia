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

Route::get('users', 'UserController@show');
Route::get('drivers', 'DriverController@show');
Route::get('drivers/{email}', 'DriverController@getDriverByEmail');
Route::post('notifications/add', 'NotificationController@add');
Route::put('notifications/{id}', 'NotificationController@update');
Route::get('notifications/device/{id}', 'NotificationController@getNotificationByDeviceId');
Route::get('notifications/driver/{id}', 'NotificationController@getNotificationByDriverId');
