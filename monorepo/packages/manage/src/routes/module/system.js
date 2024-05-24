'use strict'

export default {
	path: 'system',
	name: 'system',
	meta: { title: '系统管理' },
	component: { template: '<router-view />' },
	children: [
		{
			path: 'user',
			name: 'system-user',
			meta: { title: '用户管理' },
			component: () => import('@views/system/User.vue')
		},
		{
			path: 'role',
			name: 'system-role',
			meta: { title: '角色管理' },
			component: () => import('@views/system/Role.vue')
		},
		{
			path: 'permission',
			name: 'system-permission',
			meta: { title: '权限管理' },
			component: () => import('@views/system/Permission.vue')
		}
	]
}
