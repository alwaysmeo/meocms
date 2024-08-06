'use strict'
import { isEmpty, isEqual } from 'radash'
import { defineStore } from 'pinia'
import STORAGE_KEY from '@utils/storageKey'
import usersApi from '@apis/users'

const storeKey = STORAGE_KEY.SIDEBAR

export default defineStore(storeKey, {
	state: () => {
		return {
			list: new Array(),
			collapsed: false
		}
	},
	actions: {
		async getList() {
			if (isEmpty(this.list)) {
				const { code, data } = await usersApi.permissionsList()
				if (isEqual(code, 200)) this.list = data
			}
			return this.list
		},
		getCollapsed() {
			return this.collapsed
		},
		changeCollapsed() {
			this.collapsed = !this.collapsed
		},
		clear() {
			this.list = new Array()
		}
	},
	persist: {
		paths: ['collapsed'],
		enabled: true,
		key: storeKey
	}
})
