'use strict'
import { message } from 'ant-design-vue'
import { useUserInfoStore } from '@stores/userInfoStore'
import { isEqual, isString, isNumber, isFunction } from 'radash'
import localforage from '@utils/localforage'
import routes from '@routes'
import i18n from '@language'

const { t } = i18n.global

// 错误码映射表
const mapping = {
	1000: t('meo.request.error.3000'),
	2000: t('meo.request.error.2000'),
	2001: t('meo.request.error.2001'),
	2002: t('meo.request.error.2002'),
	2003: t('meo.request.error.2003'),
	3000: t('meo.request.error.3000'),
	3001: t('meo.request.error.3001'),
	3002: t('meo.request.error.3002'),
	3003: t('meo.request.error.3003'),
	3004: t('meo.request.error.3004'),
	3005: t('meo.request.error.3005'),
	4000: t('meo.request.error.4000'),
	5000: t('meo.request.error.5000')
}

export default async ({ response, error }) => {
	if (isNumber(response?.data?.code) && mapping[response.data.code]) {
		if (isString(mapping[response.data.code])) message.error(mapping[response.data.code])
		if (isFunction(mapping[response.data.code])) mapping[response.data.code](response)
	}
	if (isEqual(error?.response?.status, 401)) {
		message.error(t('meo.request.error.401'))
		const userInfoStore = useUserInfoStore()
		await userInfoStore.clear()
		localStorage.clear()
		localforage.clear()
		routes.replace({ name: 'login' })
	}
}
