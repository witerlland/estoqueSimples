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

Route::group(['prefix' => '/', 'middleware' => 'auth'], function(){
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/home', 'HomeController@index')->name('home');

    Route::get('/produtos', 'Dash\ProductsController@index')->name('products');
    Route::get('/clientes', 'Dash\ClientsController@index')->name('clients');
    Route::get('/vendas', 'Dash\SalesController@index')->name('sales.index');
    Route::get('/vendas/nova', 'Dash\SalesController@new')->name('sales.new');
});
