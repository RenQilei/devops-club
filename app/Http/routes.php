<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// 登录路由...
Route::get('/auth/login', 'Auth\AuthController@getLogin');
Route::post('/auth/login', 'Auth\AuthController@postLogin');
Route::get('/auth/logout', 'Auth\AuthController@getLogout');

// 注册路由...
Route::get('/auth/register', 'Auth\AuthController@getRegister');
Route::post('/auth/register', 'Auth\AuthController@postRegister');

Route::get('/', 'CommonController@index');

Route::group(['middleware' => 'auth'], function()
{
    Route::get('/home/{$name}', 'HomeController@index');

    Route::resource('/article', 'ArticleController', ['except' => ['index', 'show']]);
    Route::post('/article/image/upload', 'ArticleController@uploadImage');
    Route::post('/article/{id}/set_essential', 'ArticleController@setEssential');
    Route::post('/article/{id}/set_wiki', 'ArticleController@setWiki');

    Route::resource('/category', 'CategoryController');
});

Route::resource('/article', 'ArticleController', ['only' => ['index', 'show']]);