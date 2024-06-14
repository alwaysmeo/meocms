import { isObject, isString } from 'radash'
import { Modal } from '@opentiny/vue'

export const useMessage = (value, status) => {
	if (isString(value)) return Modal.message({ message: value, status, duration: 1500 })
	if (isObject(value)) return Modal.message({ duration: 1500, ...value })
}
