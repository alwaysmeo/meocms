'use strict'

export default {
	path: 'home',
	name: 'home',
	meta: { title: '首页' },
	component: () => import('@views/Home.vue')
}
