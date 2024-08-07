const pages = import.meta.glob('./*.js', {
	eager: true,
	import: 'default'
})
const exports = {}
for (const key in pages) Object.assign(exports, pages[key])

console.info('❤️ hooks:', Object.keys(exports))
export default exports
