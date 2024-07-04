import localforage from 'localforage'

localforage.config({
	driver: [localforage.INDEXEDDB, localforage.WEBSQL, localforage.LOCALSTORAGE],
	name: 'meocms',
	storeName: 'store',
	version: 1.0,
	description: 'meocms bucket'
})

export default localforage
