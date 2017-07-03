<?php

Auth::routes();
Route::post('/password/first', 'Auth\SetFirstPasswordController@reset');
Route::get('/logout', 'Auth\LoginController@logout');
