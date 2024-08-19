import { message } from 'ant-design-vue'
import copy from 'copy-to-clipboard'

export default {
	/** 字符串首字母转大写 */
	useCapitalCase: (str) => {
		return str.charAt(0).toUpperCase() + str.slice(1)
	},
	/** 复制文本内容至剪贴板 */
	useCopyText: (text, prompt = '已复制') => {
		const res = copy(text)
		if (res) prompt && message.success(prompt)
		else message.error('复制失败，浏览器暂不支持')
	}
}
