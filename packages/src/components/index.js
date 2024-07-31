const pages = import.meta.glob('./*.vue', {
	eager: true,
	import: 'default'
})

const exports = {}
for (const key in pages) exports[key.match(/\.\/(.*)\.vue$/)[1]] = pages[key]

export const { Tree } = exports
