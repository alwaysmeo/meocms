<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API router for your application. These
| router are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

use App\Http\Controllers\api\AccountController;
use App\Http\Controllers\api\PermissionsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
	return $request->user();
})->middleware('auth:sanctum');

Route::get('/permissions/list', [PermissionsController::class, 'list']); // 获取用户权限列表

Route::group(['prefix' => 'account'], function () {
	Route::post('/login', [AccountController::class, 'login']); // 用户登录
});
