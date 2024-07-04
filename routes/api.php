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
use App\Http\Controllers\api\CommonController;
use App\Http\Controllers\api\PermissionsController;
use App\Http\Controllers\api\TestController;
use App\Http\Controllers\api\UsersController;
use Illuminate\Support\Facades\Route;

/* 测试 */
Route::get('/test', [TestController::class, 'test']);

Route::group(['prefix' => 'account'], function () {
	/* 令牌失效 */
	Route::get('/invalid', [AccountController::class, 'invalid'])->name('login');
	/* 用户登录 */
	Route::post('/login', [AccountController::class, 'login']);
	/* 用户注册/新增 */
	Route::post('/register', [AccountController::class, 'register']);
	/* 用户登出 */
	Route::post('/logout', [AccountController::class, 'logout'])->middleware('auth:api');
});

Route::group(['prefix' => 'common'], function () {
	/* 生成验证码 */
	Route::post('/captcha', [CommonController::class, 'captcha']);
});

Route::group(['middleware' => 'auth:api'], function () {
	/* 文件上传 */
//	Route::post('upload/file', [UploadController::class, 'uploadFile']);
	/* 获取用户权限列表 */
	Route::get('/permissions/list', [PermissionsController::class, 'list']);


	Route::group(['prefix' => 'users'], function () {
		/* 获取用户列表 */
		Route::get('/list', [UsersController::class, 'list']);
	});
});
