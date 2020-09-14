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



Route::get('/csv', 'CalculationController@export')->name('csv');

Route::post('/upload', 'CalculationController@import')->name('upload');

Route::resource('/calculation', 'CalculationController');
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/history', 'CalculationController@home')->name('history');







