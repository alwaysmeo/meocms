'use strict'

import request from '@utils/request'

/**
 * 注册
 * @param account * 账号
 * @param password * 密码
 * @param captcha * 验证码 <{ key, value }>
 */
async function register(data) {
	return await request.post(`/api/account/register`, data)
}

/**
 * 登录
 * @param account * 账号
 * @param password * 密码
 * @param captcha * 验证码 <{ key, value }>
 */
async function login(data) {
	return await request.post(`/api/account/login`, data)
}

/**
 * 登出
 */
async function logout() {
	return await request.post(`/api/account/logout`)
}

export default { register, login, logout }
