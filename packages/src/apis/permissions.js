'use strict'

import request from '@utils/request'

/**
 * 权限列表
 */
async function list() {
	return await request.get(`/api/permissions/list`)
}

export default { list }
