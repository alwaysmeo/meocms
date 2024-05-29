'use strict'
import { isEmpty } from 'radash'
import { defineStore } from 'pinia'
import STORAGE_KEY from '@utils/storageKey'

const storeKey = STORAGE_KEY.TABS_LIST
export const useTabsListStore = defineStore(storeKey, {
	state: () => new Object(),
	actions: {
		add({ key, value }) {
			if (!isEmpty(key)) Object.assign(this.$state, { [key]: value })
		},
		remove(key) {
			if (isEmpty(this.$state)) return
			delete this.$state[key]
		},
		get() {
			return this.$state
		},
		clear() {
			this.$state = new Object()
		}
	},
	getters: {},
	persist: {
		enabled: true,
		key: storeKey
	}
})
