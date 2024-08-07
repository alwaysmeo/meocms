'use strict'
import { isEmpty } from 'radash'
import { defineStore } from 'pinia'
import STORAGE_KEY from '@utils/storageKey'
import localforage from '@utils/localforage'

const storeKey = STORAGE_KEY.TABS_LIST
export default defineStore(storeKey, {
	state: () => ({ data: new Object() }),
	actions: {
		async add({ key, value }) {
			if (isEmpty(this.data)) await this.get()
			Object.assign(this.data, { [key]: value })
			await localforage.setItem(storeKey, toRaw(this.data))
		},
		async remove(key) {
			if (isEmpty(this.data)) return
			delete this.data[key]
			await localforage.setItem(storeKey, toRaw(this.data))
		},
		async get() {
			Object.assign(this.data, (await localforage.getItem(storeKey)) ?? {})
			return this.data
		},
		async clear() {
			this.data = new Object()
			await localforage.removeItem(storeKey)
		}
	}
})
