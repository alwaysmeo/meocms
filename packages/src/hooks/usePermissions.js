import { isEqual } from 'radash'
import usersApi from '@apis/users'

export default {
	usePermissions: async () => {
		const route = useRoute()
		const { code, data } = await usersApi.permissionsChild({ parent_code: route.name })
		if (isEqual(code, 200)) {
			return data.map((item) => item.code)
		} else {
			return []
		}
	}
}
