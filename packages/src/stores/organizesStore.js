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
			return {
				checked: this.checked,
				list: this.list
			}
		},
		async set(list) {
			const that = this
			const { code, data } = await organizesApi.list()
			if (isEqual(code, 200)) {
				this.list = data.list
				if (isEmpty(this.checked)) {
					this.checked = this.list[0]
				} else {
					const arr = data.list.filter((item) => isEqual(item.id, that.checked.id))
					if (!arr.length) this.checked = this.list[0]
				}
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
		paths: ['checked'],
		enabled: true,
		key: storeKey
	}
})
