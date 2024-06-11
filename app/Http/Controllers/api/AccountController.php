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
use Illuminate\Support\Str;

class AccountController extends Controller
{
	/** 用户登录 */
	public function login(Request $request): Response
	{
		$req = $request->only(['account', 'password']);
		$validator = Validator::make($req, [
			'account' => 'required|email',
			'password' => 'required|regex:/^[a-zA-Z0-9]+$/|between:6,20',
			'browser_fingerprint' => 'between:0,180'
		]);
		if (!$validator->passes()) return $this->fail(null, $validator->errors()->first(), 5000);
		// 用户是否存在
		$user = Users::query()->where('email', $req['account'])->whereNull('deleted_at')->first();
		if (!$user) return $this->fail(null, Mapping::$code['3002'], 3002);
		// 验证密码
		if (!Hash::check($req['password'], $user->password)) return $this->fail(null, Mapping::$code['3003'], 3003);
		// 验证账号禁封状态
		if ($user->status === 0) return $this->fail(null, Mapping::$code['3000'], 3000);
		// 生成token
		$token = Str::random(128);
		Users::query()->where('ulid', $user->ulid)->update(['token' => $token, 'last_login_at' => date('Y-m-d H:i:s')]);
		// 添加登录记录
		$common = new Common();
		AccountRecord::query()->create([
			'user_id' => $user->ulid,
			'control_user_id' => $user->ulid,
			'type' => 2,
			'description' => '账号登录',
			'ip' => $common->ip($request)
		]);
		return $this->success($user);
	}

	public function register(Request $request): Response
	{
		$req = $request->only(['account', 'password']);
		$validator = Validator::make($req, [
			'account' => 'required|email',
			'password' => 'required|regex:/^[a-zA-Z0-9]+$/|between:6,20'
		]);
		if (!$validator->passes()) return $this->fail(null, $validator->errors()->first(), 5000);
		// 查询账号是否已存在
		$user = Users::query()->where('email', $req['account'])->whereNull('deleted_at')->first();
		if ($user) return $this->fail(null, Mapping::$code['3002'], 3002);
		// 创建用户
		Users::query()->create([
			'ulid' => Str::ulid(),
			'email' => $req['account'],
			'password' => Hash::make($req['password']),
			'nickname' => '用户' . time()
		]);
		return $this->success();
	}
}
