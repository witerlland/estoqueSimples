<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => '/user'], function(){
    Route::post('/new', 'Auth\RegisterController@new');
});

Route::group(['prefix' => '/products'], function(){
    Route::get('/{id?}', 'Dash\ProductsController@get');
    Route::post('/createOrUpdate', 'Dash\ProductsController@createOrUpdate');
});

Route::group(['prefix' => '/clients'], function(){
    Route::get('/{id?}', 'Dash\ClientsController@get');
    Route::post('/createOrUpdate', 'Dash\ClientsController@createOrUpdate');
});

Route::group(['prefix' => '/sales'], function(){
    Route::get('/{id?}', 'Dash\SalesController@get');
    Route::post('/create', 'Dash\SalesController@create');
});