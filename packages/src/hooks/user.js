import { isFunction } from 'radash'
import stores from '@stores'
import localforage from '@utils/localforage'

export default {
	useLogout: (callback) => {
		for (const key in stores) stores[key]().clear()
		localStorage.clear()
		localforage.clear()
		if (isFunction) callback()
	}
}
