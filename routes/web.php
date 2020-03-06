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

Route::get('users/{user:slug}/edit', 'UsersController@edit')->middleware('auth', 'authorized')->name('users.edit');
Route::patch('users/{user:slug}', 'UsersController@update')->middleware('auth', 'authorized')->name('users.update');

Route::group([
    'prefix' => 'users',
    'middleware' => ['auth', 'parent']
], function () {
    Route::get('', 'UsersController@index')->name('users.index');
    Route::get('create', 'UsersController@create')->name('users.create');
    Route::post('', 'UsersController@store')->name('users.store');
    Route::get('{user:slug}', 'UsersController@show')->name('users.show');
    Route::delete('{user:slug}', 'UsersController@destroy')->name('users.delete');
});

Route::group([
    'prefix' => 'vendors',
    'middleware' => ['auth', 'parent']
], function () {
    Route::get('', 'VendorsController@index')->name('vendors.index');
    Route::get('create', 'VendorsController@create')->name('vendors.create');
    Route::post('', 'VendorsController@store')->name('vendors.store');
    Route::get('{vendor}', 'VendorsController@show')->name('vendors.show');
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

Route::group([
    'prefix' => 'transfers',
    'middleware' => ['auth', 'parent']
], function () {
    Route::get('create', 'TransfersController@create')->name('transfers.create');
    Route::post('', 'TransfersController@store')->name('transfers.store');
});

Route::group([
    'prefix' => 'cards',
    'middleware' => ['auth', 'parent']
], function () {
    Route::get('', 'CardsController@index')->name('cards.index');
    Route::get('create', 'CardsController@create')->name('cards.create');
    Route::post('', 'CardsController@store')->name('cards.store');
    Route::get('{card}', 'CardsController@show')->name('cards.show');
    Route::get('{card}/edit', 'CardsController@edit')->name('cards.edit');
    Route::patch('{card}', 'CardsController@update')->name('cards.update');
    Route::delete('{card}', 'CardsController@destroy')->name('cards.delete');
});

Route::get('api/cards/{vendors}', 'CardsController@vendorsCards')->middleware('auth', 'parent')->name('api.cards.vendor');
Route::get('api/cards/{vendors}/{user?}', 'CardsController@userCards')->middleware('auth', 'parent')->name('api.cards.user');
Route::get('api/users/{user:slug}/vendors', 'UsersController@vendorsList')->middleware('auth', 'parent')->name('api.users.vendors');
Route::get('api/users/{excludeUser?}', 'UsersController@usersList')->middleware('auth', 'parent')->name('api.users.list');
Route::post('api/users/{user:slug}/avatar', 'UserAvatarsController@store')->middleware('auth', 'authorized')->name('api.users.avatar.add');
Route::delete('api/users/{user:slug}/avatar', 'UserAvatarsController@destroy')->middleware('auth', 'authorized')->name('api.users.avatar.delete');
