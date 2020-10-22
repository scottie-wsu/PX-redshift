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
Route::get('/', function () {
    return view('auth.login');
})->middleware('guest');
Route::get('/guest','guestController@index')->name('guest')->middleware('guest');
Route::post('/guest','guestController@store')->middleware('guest');
Route::any('/search','CalculationController@search')->name('search');
Route::get('/myaccount', 'MyAccountController@show')->name('MyAccount');
Route::post('/myaccount', 'MyAccountController@postChangePasswordForm')->name('MyAccountPassword');
//Route::post('/myaccount1', 'MyAccountController@update')->name('MyAccountUpdate');
Route::post('/myaccount1', 'MyAccountController@postAccountInfoForm')->name('MyAccountUpdate');

//csv file submit
Route::post('/upload', 'CalculationController@import')->name('upload');

Route::resource('/calculation', 'CalculationController');
Route::get('/home', 'HomeController@index')->name('home');
Route::post('/home', 'HomeController@fail')->name('homefail');


Route::get('/history', 'CalculationController@home')->name('history');

Route::get('/progress', 'ProgressController@Index')->name('progress');
Route::get('/progressAjax', 'CalculationController@progressAjax')->name('progressAjax');

Route::get('/zipAll', 'CalculationController@zipAll')->name('zipAll');
Route::post('/zipJob', 'CalculationController@zipJob')->name('zipJob');

Route::get('/guestAjax', 'guestController@guestAjax')->name('guestAjax')->middleware('guest');
Route::get('/guestResult', 'guestController@guestResult')->name('guestResult')->middleware('guest');

Route::get('/fetch-progress', 'ProgressController@FetchProgress')->name('fetchProgress');