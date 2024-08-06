import hooks from '@hooks'

const pages = import.meta.glob('./*.js', {
	eager: true,
	import: 'default'
})
const exports = {}
for (const key in pages) {
	if (!['./pinia.js'].includes(key)) {
		exports[`use${hooks.useCapitalCase(key.match(/\.\/(.*)\.js$/)[1])}Store`] = pages[key]
	}
}

export default exports
