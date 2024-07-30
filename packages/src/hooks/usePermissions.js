import { isEqual } from 'radash'
import permissionsApi from '@apis/permissions'
export default async function usePermissions() {
	const route = useRoute()
	const { code, data } = await permissionsApi.children({
		parent_code: route.name
	})
	if (isEqual(code, 200)) {
		return data.map((item) => item.code)
	} else {
		return []
	}
}
