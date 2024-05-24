'use strict'
import nprogress from '@utils/nprogress'

export default (req, res, next) => {
	document.title = req.meta.title
	nprogress.done()
}
