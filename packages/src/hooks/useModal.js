import { Modal } from 'ant-design-vue'

export const modal = async (params) => {
	Modal.confirm({
		centered: true,
		...params,
		onOk: () => params?.confirm?.(),
		onCancel: () => params?.cancel?.()
	})
}
