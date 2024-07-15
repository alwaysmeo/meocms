'use strict'
import { isEmpty, isEqual } from 'radash'
import { defineStore } from 'pinia'
import STORAGE_KEY from '@utils/storageKey'
import organizesApi from '@apis/organizes'

const storeKey = STORAGE_KEY.ORGANIZES
export const useOrganizesStore = defineStore(storeKey, {
	state: () => {
		return {
			checked: new Object(),
			list: new Array()
		}
	},
	actions: {
		async get() {
			const that = this
			if (isEmpty(this.list)) {
				const { code, data } = await organizesApi.list()
				if (isEqual(code, 200)) this.list = data.list
			}
			if (isEmpty(this.checked)) {
				Object.assign(that.checked, that.list[0])
			}
			return {
				checked: this.checked,
				list: this.list
			}
		},
		change(data) {
			Object.assign(this.checked, data)
		},
		clear() {
			this.checked = new Object()
			this.list = new Array()
		}
	},
	persist: {
		paths: ['checked', 'list'],
		enabled: true,
		key: storeKey
	}
})
