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

//Auth::routes();
//Route::get('/home', 'HomeController@index');

// usamos implicit model binding (Vinculación de modelo implícita)
Route::get('posts/{post}-{slug}', [
    'as' => 'posts.show',
    'uses' => 'ShowPostController'
])->where('posts', '\d+');

// {category?}: puedo pasar o no el slug de la categoria
Route::get('posts-pendientes', [
    'uses' => 'ListPostController',
    'as' => 'posts.pending'
]);

Route::get('posts-completados', [
    'uses' => 'ListPostController',
    'as' => 'posts.completed'
]);

Route::get('{category?}', [
    'uses' => 'ListPostController',
    'as' => 'posts.index'
]);

