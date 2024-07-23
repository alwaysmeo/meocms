'use strict'

import request from '@utils/request'

export default {
	/**
	 * 注册
	 * @param account * 账号
	 * @param password * 密码
	 * @param captcha * 验证码 <{ key, value }>
	 */
	register: async function (data) {
		return await request.post(`/api/account/register`, data)
	},

	/**
	 * 登录
	 * @param account * 账号
	 * @param password * 密码
	 * @param captcha * 验证码 <{ key, value }>
	 */
	login: async function (data) {
		return await request.post(`/api/account/login`, data)
	},

	/**
	 * 登出
	 */
	logout: async function () {
		return await request.post(`/api/account/logout`)
	}
}
