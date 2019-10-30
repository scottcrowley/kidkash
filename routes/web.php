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
Route::get('kids/{user}/edit', 'KidsController@edit')->middleware('auth', 'authorized')->name('kids.edit');
Route::patch('kids/{user}', 'KidsController@update')->middleware('auth', 'authorized')->name('kids.update');

Route::group([
    'prefix' => 'kids',
    'middleware' => ['auth', 'parent']
], function () {
    Route::get('create', 'KidsController@create')->name('kids.create');
    Route::post('', 'KidsController@store')->name('kids.store');
    Route::delete('{kid}', 'KidsController@destroy')->name('kids.delete');
});

Route::group([
    'prefix' => 'vendors',
    'middleware' => ['auth', 'parent']
], function () {
    Route::get('', 'VendorsController@index')->name('vendors.index');
    Route::get('create', 'VendorsController@create')->name('vendors.create');
    Route::post('', 'VendorsController@store')->name('vendors.store');
    Route::get('{vendor}/edit', 'VendorsController@edit')->name('vendors.edit');
    Route::patch('{vendor}', 'VendorsController@update')->name('vendors.update');
    Route::delete('{vendor}', 'VendorsController@destroy')->name('vendors.delete');
});

Route::group([
    'prefix' => 'transactions',
    'middleware' => ['auth', 'parent']
], function () {
    Route::get('', 'TransactionsController@index')->name('transactions.index');
    Route::get('create', 'TransactionsController@create')->name('transactions.create');
    Route::post('', 'TransactionsController@store')->name('transactions.store');
    Route::get('{transaction}/edit', 'TransactionsController@edit')->name('transactions.edit');
    Route::patch('{transaction}', 'TransactionsController@update')->name('transactions.update');
    Route::delete('{transaction}', 'TransactionsController@destroy')->name('transactions.delete');
});

Route::post('api/users/{user}/avatar', 'UserAvatarsController@store')->middleware('auth', 'authorized')->name('api.users.avatar.add');
Route::delete('api/users/{user}/avatar', 'UserAvatarsController@destroy')->middleware('auth', 'authorized')->name('api.users.avatar.delete');
