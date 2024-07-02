'use strict'
import { useMessage } from '@hooks/useMessage'
import { useUserInfoStore } from '@stores/userInfoStore'
import { isString } from 'radash'
import routes from '@routes'
import i18n from '@language'

const { t } = i18n.global

// 错误码映射表
const mapping = {
	401: () => {
		useMessage(t('meo.request.error.401'), 'error')
		const userInfo = useUserInfoStore()
		userInfo.clear()
		routes.replace({ name: 'login' })
	},
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

export default (data, source) => {
	if (mapping[data.code]) {
		if (isString(mapping[data.code])) useMessage(mapping[data.code], 'error')
		mapping[data.code](data)
	}
}
