<?php

namespace App\Services;

class Mapping
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
}