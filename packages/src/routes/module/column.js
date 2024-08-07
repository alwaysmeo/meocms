'use strict'

import component from '@components/layout/Component.vue'

export default {
	path: 'column',
	name: 'column',
	meta: { title: '栏目管理' },
	component,
	children: [
		{
			path: 'manage',
			name: 'column-manage',
			meta: { title: '栏目管理' },
			component: () => import('@views/column/Manage.vue')
		},
		{
			path: 'model',
			name: 'column-model',
			meta: { title: '模型管理' },
			component: () => import('@views/column/Model.vue')
		}
	]
}
