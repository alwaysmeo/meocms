'use strict'

import request from '@utils/request'

/**
 * 注册
 * @param account 账号
 * @param password 密码
 * @param captcha 验证码
 */
async function register({ account, password, captcha }) {
	return await request.post(`/api/account/register`, { account, password, captcha })
}

/**
 * 登录
 * @param account 账号
 * @param password 密码
 */
async function login({ account, password }) {
	return await request.post(`/api/account/login`, { account, password })
}

/**
 * 登出
 */
async function logout() {
	return await request.post(`/api/account/logout`)
}

export default { register, login, logout }
