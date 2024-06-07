import Axios from 'axios'
import { Loading } from '@opentiny/vue'
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
		return response
	},
	(error) => {
		return Promise.reject(error)
	}
)

axios.interceptors.response.use(
	(response) => {
		loading.close()
		return response.data
	},
	(error) => {
		loading.close()
		return Promise.reject(error)
	}
)

export default axios
