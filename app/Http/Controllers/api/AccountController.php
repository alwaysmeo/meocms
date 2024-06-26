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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
	/* 令牌失效回调 */
	public function invalid(): Response
	{
		return $this->fail(null, Mapping::$code['1000'], 1000);
	}

	/* 登录 */
	public function login(Request $request): Response
	{
		$req = $request->only(['account', 'password', 'captcha']);
		$validator = Validator::make($req, [
			'account' => 'required|email',
			'password' => 'required|regex:/^[a-zA-Z0-9]+$/|between:6,20'
		]);
		if (!$validator->passes()) return $this->fail(null, $validator->errors()->first(), 5000);
		/* 校验验证码 */
		if (isset($req['captcha'])) {
			if (!captcha_api_check($req['captcha']['value'], $req['captcha']['key'])) return $this->fail(null, Mapping::$code['3005'], 3005);
		}
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
		$user->update(['last_login_at' => date('Y-m-d H:i:s')]);
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
		Cache::put('user-' . $user->getAuthIdentifier(), $token->plainTextToken, now()->addMinutes($cache_duration));
		return $this->success($user);
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
			'nickname' => '用户' . time()
		]);
		return $this->success();
	}

	/* 登出 */
	public function logout(Request $request): Response
	{
		$request->user()->currentAccessToken()->delete();
		AccountRecord::query()
			->where(['type' => 2, 'user_id' => $request->user()->ulid])
			->latest('id')
			->update(['updated_at' => date('Y-m-d H:i:s')]);
		Cache::forget('user-' . $request->user()->ulid);
		return $this->success();
	}
}
