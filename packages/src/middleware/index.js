'use strict'

import after from './module/after'
import before from './module/before'

export default (router) => {
    router.beforeEach(before)
    router.afterEach(after)
}
