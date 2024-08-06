'use strict'
import { isEmpty } from 'radash'
import { defineStore } from 'pinia'
import STORAGE_KEY from '@utils/storageKey'
import localforage from '@utils/localforage'

const storeKey = STORAGE_KEY.USER_INFO
export default defineStore(storeKey, {
	state: () => ({ data: new Object() }),
	actions: {
		async set(data) {
			if (!isEmpty(data)) {
				Object.assign(this.data, data)
				await localforage.setItem(storeKey, toRaw(this.data))
			}
		},
		async get() {
			if (isEmpty(this.data)) {
				Object.assign(this.data, await localforage.getItem(storeKey))
			}
			return this.data
		},
		async clear() {
			this.data = new Object()
			await localforage.removeItem(storeKey)
		}
	}
})
