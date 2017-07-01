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

Route::get('/authuser', 'AuthuserController@show');
Route::resource('profile', 'ProfileController');
Route::resource('usergroup', 'UsergroupController');
Route::resource('right', 'RightController');

Route::patch('/user/password/{user}', 'UserController@password');
Route::resource('user', 'UserController');
