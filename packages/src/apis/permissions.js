'use strict'

import request from '@utils/request'

/**
 * 获取权限列表
 * @param organize_id * 组织ID
 */
async function list(data) {
	return await request.get(`/api/permissions/list`, { params: data })
}

export default { list }
