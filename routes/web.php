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

Route::get('kids/', 'KidsController@index')->middleware('auth')->name('kids.index');
Route::get('kids/{kid}/edit', 'KidsController@edit')->middleware('auth', 'authorized')->name('kids.edit');
Route::patch('kids/{kid}', 'KidsController@update')->middleware('auth', 'authorized')->name('kids.update');

Route::group([
    'prefix' => 'kids',
    'middleware' => ['auth', 'parent']
], function () {
    Route::get('create', 'KidsController@create')->name('kids.create');
    Route::post('', 'KidsController@store')->name('kids.store');
    Route::delete('{kid}', 'KidsController@destroy')->name('kids.delete');
});

Route::post('api/users/{user}/avatar', 'UserAvatarController@store');
