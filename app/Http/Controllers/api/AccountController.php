<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\AccountRecord;
use App\Models\Users;
use App\Services\Common;
use App\Services\Mapping;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
	private Common $common;
	private array $code;

	public function __construct()
	{
		$this->common = new Common();
		$this->code = Mapping::$code;
	}

	/* 令牌失效回调 */
	public function invalid(): Response
	{
		return $this->fail(null, $this->code['1000'], 1000);
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
			if (!captcha_api_check($req['captcha']['value'], $req['captcha']['key'])) return $this->fail(null, $this->code['3005'], 3005);
		}
		/* 用户是否存在 */
		$user = Users::query()->where('email', $req['account'])->whereNull('deleted_at')->first();
		if (!$user) return $this->fail(null, $this->code['3001'], 3001);
		/* 验证密码 */
		if (!Hash::check($req['password'], $user->getAuthPassword())) return $this->fail(null, $this->code['3003'], 3003);
		/* 验证账号禁封状态 */
		if ($user->status === 0) return $this->fail(null, $this->code['3000'], 3000);
		/* 生成令牌 */
		$token = $user->createToken(strtolower(env('APP_NAME')));
		$user->setRememberToken($token->plainTextToken);
		/* 更新用户登录信息 */
		$user->update([
			'last_login_at' => date('Y-m-d H:i:s'),
			'platform' => str_replace('"', '', $request->server('HTTP_SEC_CH_UA_PLATFORM')) ?? '未知'
		]);
		/* 添加登录记录 */
		AccountRecord::query()->create([
			'user_ulid' => $user->getAuthIdentifier(),
			'control_user_ulid' => $user->getAuthIdentifier(),
			'type' => 2,
			'description' => '账号登录',
			'ipv4' => $this->common->ipv4($request),
			'ipv6' => $this->common->ipv6($request)
		]);
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
		if ($user) return $this->fail(null, $this->code['3002'], 3002);
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
		/* 添加登出记录 */
		AccountRecord::query()->create([
			'user_ulid' => $request->user()->getAuthIdentifier(),
			'control_user_ulid' => $request->user()->getAuthIdentifier(),
			'type' => 3,
			'description' => '账号登出',
			'ipv4' => $this->common->ipv4($request),
			'ipv6' => $this->common->ipv6($request)
		]);
		return $this->success();
	}
}
