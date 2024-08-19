import { Modal } from 'ant-design-vue'

export default {
	/** 对话确认弹窗 */
	useModalConfirm: async (options) => {
		Modal.confirm({
			title: '提示',
			centered: true,
			...options,
			onOk: async () => options.confirm && (await options.confirm()),
			onCancel: async () => options.cancel && (await options.cancel())
		})
	}
}
