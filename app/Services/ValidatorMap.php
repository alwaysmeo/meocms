<?php

namespace App\Services;

class ValidatorMap
{
	public static array $code = [
		'1000' => 'Token失效，请重新登录',

		'2000' => '请勿频繁获取验证码',
		'2001' => '今日发送验证码次数已达上限',
		'2002' => '验证码错误或已过期',
		'2003' => '验证码发送失败',

		'3000' => '账号已被禁用',
		'3001' => '账号不存在',
		'3002' => '账号已存在',
		'3003' => '账号或密码错误',
		'3004' => '旧密码验证错误',

		'4000' => '上传失败',

		'5000' => '参数错误',

		'6000' => '已有申请在审核中，请勿重复提交',

		'7000' => '当前QQ账号暂未绑定本站点邮箱',
	];


	// 自定义验证错误信息
	public static array $message = [
		'required' => ':attribute参数错误',
		'in' => ':attribute参数错误',
		'account.email' => '账号格式错误',
		'password.regex' => '密码必须是字母或数字',
		'password.between' => '密码长度必需在:min至:max位之间',
		'code.digits' => '验证码必须是6位数字',
		'image.mimes' => '文件格式格式错误',
		'image.between' => '文件大小超过限制',
		'file.mimes' => '文件格式格式错误',
		'file.between' => '文件大小超过限制',
	];
}
