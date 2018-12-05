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

/*
|--------------------------------------------------------------------------
| Account
|--------------------------------------------------------------------------
*/

Route::post(
    'account',                                             'AccountController@store'
);

Route::get(
    'account',                                             'AccountController@show'
)->middleware('auth:api');

/*
|--------------------------------------------------------------------------
| Article
|--------------------------------------------------------------------------
*/

Route::get(
    'articles',                                            'ArticleController@index'
);

Route::post(
    'articles',                                            'ArticleController@store'
)->middleware('auth:api');

Route::get(
    'articles/{id}',                                       'ArticleController@show'
);

Route::patch(
    'articles/{id}',                                       'ArticleController@update'
)->middleware('auth:api');
