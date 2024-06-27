import Axios from 'axios'
import { Loading } from '@opentiny/vue'
import { useMessage } from '@hooks/useMessage'
import { useUserInfoStore } from '@stores/userInfoStore'
import { isEqual } from 'radash'
import router from '@routes'
import i18n from '@language'

const { t } = i18n.global
const domain = import.meta.env.VITE_API_DOMAIN

let loading
const axios = Axios.create({
	timeout: 30000,
	baseURL: domain
})

axios.defaults.headers.post['Content-Type'] = 'application/json'

axios.interceptors.request.use(
	(response) => {
		loading = Loading.service({ lock: true, text: 'Loading', background: '#ffffff7f' })
		const userInfo = useUserInfoStore()
		const { token } = userInfo.get()
		if (token) response.headers['Authorization'] = `Bearer ${token}`
		return response
	},
	(error) => {
		return Promise.reject(error)
	}
)

axios.interceptors.response.use(
	(response) => {
		if (handleError[response.data.code]) useMessage(handleError[response.data.code], 'error')
		loading.close()
		return response.data
	},
	(error) => {
		if (handleError[error.response.status]) useMessage(handleError[error.response.status], 'error')
		if (isEqual(error.response.status, 401)) {
			const userInfo = useUserInfoStore()
			userInfo.clear()
			router.replace({ name: 'login' })
		}
		loading.close()
		return Promise.reject(error)
	}
)

const handleError = {
	401: t('meo.request.error.401'),
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

export default axios
