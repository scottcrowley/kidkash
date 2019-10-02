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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group([
    'prefix' => 'kids',
    'middleware' => 'auth'
], function () {
    Route::get('', 'KidsController@index')->name('kids.index');
    Route::get('/create', 'KidsController@create')->name('kids.create');
    Route::post('', 'KidsController@store')->name('kids.store');
    Route::get('/{kid}/edit', 'KidsController@edit')->name('kids.edit');
    Route::delete('/{kid}', 'KidsController@destroy')->name('kids.delete');
    Route::patch('/{kid}', 'KidsController@update')->name('kids.update');
});
