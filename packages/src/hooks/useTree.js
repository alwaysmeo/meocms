import { isEqual } from 'radash'

export default {
	useUnfoldTree({ source, key = 'children', callback }) {
		const arr = []
		for (const element of source) {
			if (callback) callback(element)
			arr.push(toRaw(element))
			if (element[key]) this.useUnfoldTree({ source: element[key], key, callback })
		}
		return arr
	},

	useBuildTree({ source, parent_id = null, key = 'children', callback }) {
		const arr = []
		for (const element of source) {
			if (isEqual(element.parent_id, parent_id)) {
				if (callback) callback(element)
				const children = this.useBuildTree({ source: [element], parent_id: element.id, key, callback })
				if (children) element[key] = children
				arr.push(element)
			}
		}
		return arr
	}
}
