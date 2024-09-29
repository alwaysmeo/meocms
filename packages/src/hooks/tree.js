import { isEmpty, isEqual, isArray } from 'radash'

export default {
	/** 展开树形结构 */
	useUnfoldTree(source, key = 'children') {
		if (!isArray(source)) throw new Error('Source must be an array.')
		const arr = []
		function unfoldTree(data) {
			data.forEach((item) => {
				arr.push(item)
				if (isArray(item[key])) unfoldTree(item[key])
				delete item[key]
			})
		}
		unfoldTree(JSON.parse(JSON.stringify(source)))
		console.log(arr)
		return arr
	},

	/** 构建树形结构 */
	useBuildTree(source, parent_id = null, key = 'children') {
		if (!isArray(source)) throw new Error('Source must be an array.')
		const stack = JSON.parse(JSON.stringify(source))
		const tree = []
		for (const item of stack) {
			if (isEqual(item.parent_id, parent_id)) {
				const children = this.useBuildTree(stack, item.id)
				if (!isEmpty(children)) item[key] = children
				tree.push(item)
			}
		}
		return tree
	}
}
