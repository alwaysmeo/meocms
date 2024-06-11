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
use App\Http\Controllers\api\TestController;
use Illuminate\Support\Facades\Route;

/* 测试 */
Route::get('/test', [TestController::class, 'test']);

/* 获取用户权限列表 */
Route::get('/permissions/list', [PermissionsController::class, 'list']);

Route::group(['prefix' => 'account'], function () {
	/* 用户登录 */
	Route::post('/login', [AccountController::class, 'login']);
	/* 用户注册/新增 */
	Route::post('/register', [AccountController::class, 'register']);
});
