<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/home', function() {
    return Inertia::render('App');
});
Route::get('/test', function() {
    return Inertia::render('App', [
        'component' => 'Test',
        'user' => '数据交互'
    ]);
});
