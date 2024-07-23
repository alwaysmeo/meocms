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
use App\Http\Controllers\api\OrganizesController;
use App\Http\Controllers\api\PermissionsController;
use App\Http\Controllers\api\RolesController;
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

	Route::group(['prefix' => 'roles'], function () {
		/* 获取角色列表 */
		Route::get('/list', [RolesController::class, 'list']);
		/* 新增修改角色 */
		Route::post('/upsert', [RolesController::class, 'upsert']);
		/* 删除角色 */
		Route::post('/delete', [RolesController::class, 'delete']);
		/* 获取角色关联的用户 */
		Route::get('/users', [RolesController::class, 'users']);
		/* 修改角色启用状态 */
		Route::post('/change/show', [RolesController::class, 'changeShow']);
	});

	Route::group(['prefix' => 'permissions'], function () {
		/* 获取所有权限列表 */
		Route::get('/list', [PermissionsController::class, 'list']);
		/* 新增修改权限 */
		Route::post('/upsert', [PermissionsController::class, 'upsert']);
		/* 删除权限 */
		Route::post('/delete', [PermissionsController::class, 'delete']);
		/* 修改权限启用状态 */
		Route::post('/change/show', [PermissionsController::class, 'changeShow']);
	});

	Route::group(['prefix' => 'users'], function () {
		/* 获取用户权限列表 */
		Route::get('/permissions/list', [UsersController::class, 'permissionsList']);
		/* 获取用户列表 */
		Route::get('/list', [UsersController::class, 'list']);
		/* 新增修改用户 */
		Route::post('/upsert', [UsersController::class, 'upsert']);
		/* 用户详情 */
		Route::get('/detail', [UsersController::class, 'detail']);
		/* 注销删除用户 */
		Route::post('/delete', [UsersController::class, 'delete']);
	});

	Route::group(['prefix' => 'organizes'], function () {
		/* 获取组织列表 */
		Route::get('/list', [OrganizesController::class, 'list']);
	});
});
