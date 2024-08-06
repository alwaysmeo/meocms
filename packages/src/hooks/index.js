const pages = import.meta.glob('./*.js', {
	eager: true,
	import: 'default'
})
const exports = {}
for (const key in pages) Object.assign(exports, pages[key])

export default exports
