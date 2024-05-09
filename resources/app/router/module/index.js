'use strict'

export default [
    {
        path: 'home',
        name: 'app-home',
        meta: { title: '首页' },
        component: () => import('@views/Home.vue')
    },
    {
        path: 'test',
        name: 'app-test',
        meta: { title: '测试页' },
        component: () => import('@views/Test.vue')
    }
]
