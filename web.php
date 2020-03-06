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

Route::get('/', function () {
    return view('welcome');
});
Route::get('home','CurdController@index')->name('home');
Route::get('getcurd','CurdController@getajaxindex')->name('getcurdajax');
Route::post('insertdata','CurdController@insertdata')->name('insertdata');
Route::get('getfetchdata','CurdController@getfetchdata')->name('getfetchdata');
Route::post('deletedata','CurdController@destroydata')->name('destroydata');
