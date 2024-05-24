'use strict'
import { isEmpty } from 'radash'
import { defineStore } from 'pinia'
import STORAGE_KEY from '@utils/storageKey'

const storeKey = STORAGE_KEY.USER_INFO
export const userInfoStore = defineStore(storeKey, {
	state: () => ({
		data: {}
	}),
	actions: {
		set(data) {
			const value = isEmpty(this.data) ? {} : this.data
			this.data = { ...value, ...data }
		},
		get() {
			return this.data
		},
		clear() {
			this.data = {}
		}
	},
	getters: {},
	persist: {
		enabled: true,
		strategies: [
			{
				key: storeKey,
				storage: window.localStorage
			}
		]
	}
})
