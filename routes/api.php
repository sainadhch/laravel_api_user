<?php

Route::group([

    'prefix' => 'user'

], function () {

    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::post('index', 'AuthController@index');
    Route::post('logout_other_sessions', 'AuthController@logout_other_sessions');
    Route::post('logout', 'AuthController@logout');

});