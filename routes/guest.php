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
    'uses' => 'TokenController@create',
    'as' => 'token'
]);

Route::post('token', [
    'uses' => 'TokenController@store',
    'as' => 'token'
]);

Route::get('login/{token}', [
   'uses' => 'LoginController@login',
    'as' => 'login'
]);