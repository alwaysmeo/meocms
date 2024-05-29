'use strict'
import nprogress from '@utils/nprogress'

export default (req) => {
	document.title = req.meta.title
	nprogress.done()
}
