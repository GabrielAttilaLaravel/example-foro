<?php

Route::get('register', [
    'uses' => 'RegisterController@create',
    'as' => 'register'
]);

Route::post('register', [
    'uses' => 'RegisterController@store',
    'as' => 'store'
]);

Route::get('register_confirmation', [
    'uses' => 'RegisterController@confirmation',
    'as' => 'register_confirmation'
]);

Route::get('login', [
    'uses' => 'LoginController@create',
    'as' => 'login'
]);

Route::post('login', [
    'uses' => 'LoginController@store',
]);