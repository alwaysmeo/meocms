<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/app'], function() {
    Route::get('/', function () { return view('app'); });
    Route::get('/home', function () { return view('app'); });
    Route::get('/test', function () { return view('app'); });
});
