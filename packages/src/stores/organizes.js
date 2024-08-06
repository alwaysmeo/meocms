'use strict'
import { isEmpty, isEqual, first } from 'radash'
import { defineStore } from 'pinia'
import STORAGE_KEY from '@utils/storageKey'
import localforage from '@utils/localforage'
import organizesApi from '@apis/organizes'

const storeKey = STORAGE_KEY.ORGANIZES

export default defineStore(storeKey, {
	state: () => ({ checked: new Object(), list: new Array() }),
	actions: {
		async get() {
			if (isEmpty(this.list)) {
				const store = await localforage.getItem(storeKey)
				if (store) {
					this.checked = store.checked
					this.list = store.list
				} else {
					const { code, data } = await organizesApi.list()
					if (isEqual(code, 200)) {
						const list = data.list.map((item) => ({ id: item.id, name: item.name }))
						this.list = list
						this.checked = first(list)
						await localforage.setItem(storeKey, { checked: first(list), list })
					}
				}
			}
			return {
				checked: this.checked,
				list: this.list
			}
		},
		async change(data) {
			await localforage.setItem(storeKey, { checked: toRaw(data), list: toRaw(this.list) })
			Object.assign(this.checked, data)
		},
		async clear() {
			this.checked = new Object()
			this.list = new Array()
			await localforage.removeItem(storeKey)
		}
	}
})
