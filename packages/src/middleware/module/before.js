'use strict'
import nprogress from '@utils/nprogress'

export default async (to, from, next) => {
    nprogress.start()
    next()
}
