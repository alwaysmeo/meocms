<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\AccountRecord;
use App\Models\Users;
use App\Services\Common;
use App\Services\Mapping;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
	/* 登录 */
	public function login(Request $request): Response
	{
		$req = $request->only(['account', 'password', 'browser_fingerprint']);
		$validator = Validator::make($req, [
			'account' => 'required|email',
			'password' => 'required|regex:/^[a-zA-Z0-9]+$/|between:6,20',
			'browser_fingerprint' => 'between:0,180'
		]);
		if (!$validator->passes()) return $this->fail(null, $validator->errors()->first(), 5000);
		/* 用户是否存在 */
		$user = Users::query()->where('email', $req['account'])->whereNull('deleted_at')->first();
		if (!$user) return $this->fail(null, Mapping::$code['3001'], 3001);
		/* 验证密码 */
		if (!Hash::check($req['password'], $user->getAuthPassword())) return $this->fail(null, Mapping::$code['3003'], 3003);
		/* 验证账号禁封状态 */
		if ($user->status === 0) return $this->fail(null, Mapping::$code['3000'], 3000);
		/* 生成令牌 */
		$token = $user->createToken(strtolower(env('APP_NAME')));
		$user->setRememberToken($token->plainTextToken);
		/* 更新用户信息 */
		$user->update(['browser_fingerprint' => $req['browser_fingerprint'] ?? null, 'last_login_at' => date('Y-m-d H:i:s')]);
		/* 添加登录记录 */
		$common = new Common();
		AccountRecord::query()->create([
			'user_id' => $user->getAuthIdentifier(),
			'control_user_id' => $user->getAuthIdentifier(),
			'type' => 2,
			'description' => '账号登录/登出',
			'ip' => $common->ip($request)
		]);
		$cache_duration = intval(env('CACHE_DURATION'));
		Cache::put('user-'.$user->getAuthIdentifier(), $token->plainTextToken, now()->addMinutes($cache_duration));
		$cookie = Cookie::make('token', $token->plainTextToken, $cache_duration);
		return $this->success($user)->cookie($cookie);
	}

	/* 注册 */
	public function register(Request $request): Response
	{
		$req = $request->only(['account', 'password']);
		$validator = Validator::make($req, [
			'account' => 'required|email',
			'password' => 'required|regex:/^[a-zA-Z0-9]+$/|between:6,20'
		]);
		if (!$validator->passes()) return $this->fail(null, $validator->errors()->first(), 5000);
		/* 验证账号是否已存在 */
		$user = Users::query()->where('email', $req['account'])->whereNull('deleted_at')->first();
		if ($user) return $this->fail(null, Mapping::$code['3002'], 3002);
		/* 创建用户 */
		Users::query()->create([
			'email' => $req['account'],
			'password' => Hash::make($req['password']),
			'nickname' => '用户'.time()
		]);
		return $this->success();
	}

	/* 登出 */
	public function logout(Request $request): Response
	{
		$user = auth('auth')->user();
		$request->user()->currentAccessToken()->delete();
		AccountRecord::query()
			->where(['type' => 2, 'user_id' => $user['ulid']])
			->latest('id')
			->update(['updated_at' => date('Y-m-d H:i:s')]);
		Cache::forget('user-'.$user['ulid']);
		$cookie = Cookie::make('token', null, -1);
		return $this->success()->cookie($cookie);
	}
}
