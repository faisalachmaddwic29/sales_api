<?php

use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\ForgetPasswordController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\RegisterController;
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

// Route::group(['prefix' => 'v1', 'as' => 'v1.'], function () {
//     Route::post('auth', 'Auth\AuthController@auth');
// });


Route::post('register', RegisterController::class);
Route::post('login', LoginController::class);
Route::post('forget', [ForgetPasswordController::class, 'forget']);
Route::post('validate_pin', [ForgetPasswordController::class, 'validatePin']);
Route::post('forget_password', [ForgetPasswordController::class, 'forgetPassword']);


Route::get('provinces', [AddressController::class, 'provinces']);
Route::get('regencies/{province_id?}', [AddressController::class, 'regencies']);
Route::get('districts/{regency_id?}', [AddressController::class, 'districts']);
Route::get('villages/{district_id?}', [AddressController::class, 'villages']);
