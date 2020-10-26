<?php

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

Route::get('/', 'HomeController@index')->name('home');

Auth::routes(['register' => false]);

Route::get('/auth/{network}', 'Auth\LoginController@redirectToProvider')->name('loginSocial');
Route::get('/auth/{network}/callback', 'Auth\LoginController@handleProviderCallback');

Route::group(['middleware' => ['auth'], 'prefix' => 'manager', 'as' => 'manager.'], function () {
    Route::get('settings','ManagerSettingsController@profile')->name('settings');
    Route::post('settings','ManagerSettingsController@setProfile')->name('settings');
    Route::get('settings/password','ManagerSettingsController@password')->name('settings.password');
    Route::post('settings/password','ManagerSettingsController@changePassword')->name('settings.changePassword');
    Route::get('drivers','ManagerDriversController@index')->name('drives');
    Route::get('drivers/fetch/{user_id}', 'DriverController@fetch')->name('drivers.fetch');
    Route::delete('drivers/{id}', 'DriverController@delete');
    Route::post('drivers/add', 'DriverController@add');
    Route::post('drivers/{id}', 'DriverController@update');
});
