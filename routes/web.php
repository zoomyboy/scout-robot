<?php

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
Auth::routes();

Route::get('/login', ['as' => 'login', 'uses' => 'Auth\LoginController@showLoginForm']);
Route::get('/logout', ['as' => 'logout', 'uses' => 'Auth\LoginController@logout']);

Route::post('member/{member}/billpdf', 'MemberPdfController@bill');
Route::post('member/{member}/rememberpdf', 'MemberPdfController@remember');

Route::post('/pdf/bill', 'MemberPdfController@allBill');
Route::post('/pdf/remember', 'MemberPdfController@allRemember');

Route::get('files/{file}', 'FileController@display')
	->where('file', '[0-9a-zA-Z\/\.]+');

Route::get('/{vue}', 'HomeController@index')
	->where('vue', '[a-zA-Z0-9\-\/\_]*');
