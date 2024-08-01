const pages = import.meta.glob('./*.vue', {
	eager: true,
	import: 'default'
})
const exclude = []
const exports = {}
for (const item in pages) {
	const key = item.match(/\.\/(.*)\.vue$/)[1]
	if (!exclude.includes(key)) exports[key] = pages[item]
}

export const { Index, DateRangePicker, Select, SelectInput } = exports
export default exports
