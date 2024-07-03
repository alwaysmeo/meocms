import Axios from 'axios'
import nprogress from '@utils/nprogress'
import { useUserInfoStore } from '@stores/userInfoStore'
import handleRequest from '@utils/handleRequest'

const axios = Axios.create({
	timeout: 30000,
	baseURL: import.meta.env.VITE_API_DOMAIN
})

axios.defaults.retry = 1 // 自动重试次数
axios.defaults.headers.post['Content-Type'] = 'application/json'

axios.interceptors.request.use(
	(response) => {
		nprogress.start()
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
		handleRequest({ response })
		nprogress.done()
		return response.data
	},
	(error) => {
		handleRequest({ error })
		nprogress.done()
		return Promise.reject(error)
	},
	async function axiosRetryInterceptor(error) {
		nprogress.done()
		error.config.retryCount = error.config.retryCount ?? 0
		if (error.config.retryCount >= error.config.retry) return Promise.reject(error)
		error.config.retryCount += 1
		return await axios(error.config)
	}
)

export default axios
