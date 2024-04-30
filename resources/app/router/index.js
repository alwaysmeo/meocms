'use strict'

import { createRouter, createWebHistory } from 'vue-router'
import Middleware from '../middleware'
import modules from './module'

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            redirect: '/home'
        },
        {
            path: '/home',
            name: 'home',
            meta: { title: '首页' },
            component: () => import('@views/Home.vue')
        },
        ...modules
    ]
})

Middleware(router)

export default router
