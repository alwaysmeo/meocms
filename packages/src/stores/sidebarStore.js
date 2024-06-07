'use strict'
import { isEmpty, isEqual } from 'radash'
import { defineStore } from 'pinia'
import STORAGE_KEY from '@utils/storageKey'
import permissionsApi from '@apis/permissions'

const storeKey = STORAGE_KEY.SIDEBAR_LIST
export const useSidebarStore = defineStore(storeKey, {
	state: () => new Array(),
	actions: {
		async get() {
			if (isEmpty(this.$state)) {
				const { code, data } = await permissionsApi.list()
				if (isEqual(code, 200)) this.$state = data
			}
			return this.$state
		},
		clear() {
			this.$state = new Array()
		}
	},
	getters: {}
})
