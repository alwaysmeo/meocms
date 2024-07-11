import { Modal } from 'ant-design-vue'

// 对话确认弹窗
export const useModalConfirm = async (options) => {
	Modal.confirm({
		title: '提示',
		centered: true,
		...options,
		onOk: async () => options.confirm && (await options.confirm()),
		onCancel: async () => options.cancel && (await options.cancel())
	})
}
