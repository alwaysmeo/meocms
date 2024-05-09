'use strict'
import { isEmpty } from 'radash'
import { defineStore } from 'pinia'
import STORAGE_KEY from '@utils/storageKey'

const storeKey = STORAGE_KEY.SIDEBAR_LIST
export const useSidebarStore = defineStore(storeKey, {
	state: () => ({
		list: [],
		code: [],
		collapsed: false // 侧边栏是否折叠
	}),
	actions: {
		async get() {
			return {
				list: this.list,
				code: this.code
			}
		},
		setCollaps(collapsed) {
			this.collapsed = !this.collapsed
		},
		clear() {
			this.list = []
			this.code = []
		}
	}
})
