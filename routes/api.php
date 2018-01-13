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

Route::get('country/default', 'CountryController@default');
Route::resource('country', 'CountryController');

Route::get('region/default', 'RegionController@default');
Route::resource('region', 'RegionController');

Route::resource('region', 'RegionController');

Route::resource('confession', 'ConfessionController');

Route::resource('conf', 'ConfController');

Route::get('member/table', 'MemberController@table');
Route::get('member/{member}/table', 'MemberController@tableOne');
Route::resource('member', 'MemberController');

Route::resource('gender', 'GenderController');

Route::get('info', 'ProfileController@infoForCurrentUser');

Route::get('member/{member}/payments', 'PaymentController@index');
Route::post('member/{member}/payments', 'PaymentController@store');
Route::patch('member/{member}/payments/{payment}', 'PaymentController@update');
Route::delete('payment/{payment}', 'PaymentController@destroy');

Route::get('/status', 'StatusController@index');

Route::resource('file', 'FileController');

Route::get('unit/{type}', 'UnitController@index');

Route::get('/way', 'WayController@index');

Route::post('mass/email/bill', 'MassController@bill');
Route::post('mass/email/remember', 'MassController@remember');

Route::post('nami/getmembers', 'NaMiController@getmembers');
