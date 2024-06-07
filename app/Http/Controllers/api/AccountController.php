<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Users;
use App\Services\ValidatorMap;
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
			'password' => 'required|regex:/^[a-zA-Z0-9]+$/|between:6,20'
		], ValidatorMap::$message);
		if (!$validator->passes()) return $this->fail(null, $validator->errors()->first(), 5000);
		// 用户是否存在
		$user = Users::query()->where('account', $req['account'])->whereNull('deleted_at')->first();
		if (!$user) return $this->fail(null, ValidatorMap::$code['3002'], 3002);
		// 验证密码
		if (!Hash::check($req['password'], $user->password)) return $this->fail(null, ValidatorMap::$code['3003'], 3003);
		// 验证账号禁封状态
		if ($user->status === 0) return $this->fail(null, ValidatorMap::$code['3000'], 3000);
		// 生成token
		$token = Str::random(128);
		Users::query()->where('id', $user->id)
			->update(['token' => $token, 'last_login_at' => date('Y-m-d H:i:s')]);
		// 添加登录记录
		$user->account_record()->create([
			'user_id' => $user->id,
			'control_user_id' => $user->id,
			'type' => 2,
			'description' => '登录',
			'ip' => $request->ip(),
			'longitude' => $req['longitude'],
			'altitude' => $req['altitude']
		]);
		return $this->success([
			'id' => $user->id,
			'token' => $token,
			'nickname' => $user->nickname,
			'headimg' => $user->headimg,
			'phone' => $user->phone,
			'status' => $user->status,
			'last_login_at' => $user->last_login_at,
			'created_at' => $user->created_at,
			'updated_at' => $user->updated_at,
			'deleted_at' => $user->deleted_at
		]);
	}
}
