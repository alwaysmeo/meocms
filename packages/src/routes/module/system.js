'use strict'

import component from '@components/layout/Component.vue'

export default {
	path: 'system',
	name: 'system',
	meta: { title: '系统管理' },
	component,
	children: [
		{
			path: 'users',
			name: 'system-users',
			meta: { title: '用户管理' },
			component: () => import('@views/system/Users.vue')
		},
		{
			path: 'roles',
			name: 'system-roles',
			meta: { title: '角色管理' },
			component: () => import('@views/system/Roles.vue')
		},
		{
			path: 'permissions',
			name: 'system-permissions',
			meta: { title: '权限管理' },
			component: () => import('@views/system/Permissions.vue')
		},
		{
			path: 'organizes',
			name: 'system-organizes',
			meta: { title: '组织管理' },
			component: () => import('@views/system/Organizes.vue')
		}
	]
}
