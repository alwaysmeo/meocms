'use strict'
import { message } from 'ant-design-vue'
import { isEqual, isString, isNumber, isFunction } from 'radash'
import hooks from '@hooks'
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
	5000: t('meo.request.error.5000'),
	5001: t('meo.request.error.5001'),
	5002: t('meo.request.error.5002')
}

export default async ({ response, error }) => {
	if (isNumber(response?.data?.code) && mapping[response.data.code]) {
		if (isString(mapping[response.data.code])) message.error(mapping[response.data.code])
		if (isFunction(mapping[response.data.code])) mapping[response.data.code](response)
	}
	if (isEqual(error?.response?.status, 401)) {
		hooks.useLogout(() => {
			message.error(t('meo.request.error.401'))
			routes.replace({ name: 'login' })
		})
	}
}
