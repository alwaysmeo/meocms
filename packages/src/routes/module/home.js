'use strict'

import component from '@components/layout/Component.vue'

export default {
	path: 'home',
	meta: { title: '系统管理' },
	component,
	children: [
		{
			path: '',
			name: 'home',
			meta: { title: '首页' },
			component: () => import('@views/Home.vue')
		}
	]
}
