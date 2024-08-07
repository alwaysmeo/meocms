'use strict'
import Layout from '@components/layout/Layout.vue'

const pages = import.meta.glob('./*.js', {
	eager: true,
	import: 'default'
})

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
		children: Object.values(pages)
	}
]
