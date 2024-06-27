'use strict'
import { isEmpty } from 'radash'
import { defineStore } from 'pinia'
import STORAGE_KEY from '@utils/storageKey'

const storeKey = STORAGE_KEY.USER_INFO
export const useUserInfoStore = defineStore(storeKey, {
	state: () => {
		return {
			data: new Object()
		}
	},
	actions: {
		set(data) {
			if (!isEmpty(data)) Object.assign(this.data, data)
		},
		get() {
			return this.data
		},
		clear() {
			this.data = new Object()
		}
	},
	getters: {},
	persist: {
		enabled: true,
		key: storeKey
	}
})
