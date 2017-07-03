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

Route::bind('profile', function ($id) {
	return App\User::findOrFail($id);
});

Route::get('/authuser', 'AuthuserController@show');
Route::resource('profile', 'ProfileController');
Route::patch('/profile/{profile}/password', 'ProfileController@updatePassword');

Route::resource('usergroup', 'UsergroupController');

Route::resource('right', 'RightController');

Route::resource('user', 'UserController');
