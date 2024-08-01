function useUnfoldTree(source, key = 'children') {
	const arr = []
	function tree(value) {
		for (const child of value) {
			arr.push(toRaw(child))
			if (child[key]) tree(child[key])
		}
	}
	tree(source)
	return arr
}
export default { useUnfoldTree }
