import Axios from 'axios'

const axios = Axios.create({
	timeout: 30000,
	baseUrl: ''
})

axios.defaults.headers.post['Content-Type'] = 'application/json'

axios.interceptors.request.use(
	(response) => {
		return response
	},
	(error) => {
		return Promise.reject(error)
	}
)

axios.interceptors.response.use(
	(response) => {
		return response.data
	},
	(error) => {
		return Promise.reject(error)
	}
)

export default axios
