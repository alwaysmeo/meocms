'use strict'
import Layout from '@components/layout/Layout.vue'
import home from './home'
import system from './system'

export default [
	{
		path: '/login',
		name: 'login',
		meta: { title: '登录注册', verifyLogin: false },
		component: () => import('@views/Login.vue')
	},
	{
		path: '/',
		redirect: '/home',
		component: Layout,
		children: [home, system]
	}
]
