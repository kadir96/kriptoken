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

Route::prefix('/auth')->namespace('Auth')->group(function () {
    Route::post('/register', 'RegisterController')->name('register');
    Route::post('/login', 'LoginController')->name('login');
});

Route::middleware('auth:api')->group(function () {
    Route::post('/exchange', 'ExchangeController')->name('exchange');
    Route::get('/user', 'UserController')->name('user');
    Route::get('/wallet', 'WalletController')->name('wallet');
});
