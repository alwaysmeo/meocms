'use strict'

import component from '@components/layout/Component.vue'

export default {
	path: 'content',
	name: 'content',
	meta: { title: '内容管理' },
	component,
	children: [
		{
			path: 'manage',
			name: 'content-manage',
			meta: { title: '内容管理' },
			component: () => import('@views/content/Manage.vue')
		},
		{
			path: 'model',
			name: 'content-model',
			meta: { title: '模型管理' },
			component: () => import('@views/content/Model.vue')
		},
		{
			path: 'timing',
			name: 'content-timing',
			meta: { title: '定时任务' },
			component: () => import('@views/content/Timing.vue')
		}
	]
}
