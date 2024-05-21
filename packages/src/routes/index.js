'use strict'

import { createRouter, createWebHistory } from 'vue-router'
import Middleware from '../middleware'
import module from './module'

const router = createRouter({
	history: createWebHistory(),
	routes: module
})

Middleware(router)

export default router
