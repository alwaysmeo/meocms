'use strict'
import { isEmpty, isEqual } from 'radash'
import { defineStore } from 'pinia'
import STORAGE_KEY from '@utils/storageKey'
import localforage from '@utils/localforage'
import usersApi from '@apis/users'

const storeKey = STORAGE_KEY.SIDEBAR

export default defineStore(storeKey, {
	state: () => ({ list: new Array(), collapsed: false }),
	actions: {
		async getList() {
			if (isEmpty(this.list)) {
				const store = await localforage.getItem(storeKey)
				if (store) {
					this.list = store
				} else {
					const { code, data } = await usersApi.permissionsList()
					if (isEqual(code, 200)) {
						await localforage.setItem(storeKey, data)
						this.list = data
					}
				}
			}
			return this.list
		},
		getCollapsed() {
			return this.collapsed
		},
		changeCollapsed() {
			this.collapsed = !this.collapsed
		},
		async clear() {
			this.list = new Array()
			await localforage.removeItem(storeKey)
		}
	},
	persist: {
		paths: ['collapsed'],
		enabled: true,
		key: storeKey
	}
})
