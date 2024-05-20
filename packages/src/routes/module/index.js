'use strict'
import Layout from '@components/layout/Layout.vue'
import home from './home'

export default [
	{
		path: '/login',
		name: 'login',
		meta: { title: '登录注册' },
		component: () => import('@views/Login.vue')
	},
	{
		path: '/',
		component: Layout,
		children: [home]
	}
]
