'use strict'
import { isEmpty, isEqual } from 'radash'
import nprogress from '@utils/nprogress'
import stores from '@stores'

export default async (to, from, next) => {
	nprogress.start()
	const userInfoStore = stores.useUserInfoStore()
	const userInfo = await userInfoStore.get()
	// 验证当前页面是否需要登录信息
	if (isEqual(to.meta.verifyLogin, undefined)) {
		if (isEmpty(userInfo)) return next({ name: 'login' })
		return next()
	}
	// 已登录用户强制跳转至首页
	if (isEqual(to.name, 'login') && !isEmpty(userInfo)) {
		return next({ name: 'home' })
	}
	return next()
}
