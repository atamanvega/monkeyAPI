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

Route::resource('users', 'User\UserController')->except(['create', 'edit']);
Route::put('/users/changeUserStatus/{user}', 'User\UserController@changeUserStatus');
Route::get('users/verify/{token}', 'User\UserController@verify')->name('verify');

Route::resource('customers', 'Customer\CustomerController')->except(['create', 'edit']);

