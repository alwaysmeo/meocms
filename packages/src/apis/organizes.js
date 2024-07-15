'use strict'

import request from '@utils/request'

/**
 * 组织列表
 * @param page 页码
 * @param limit 每页条数
 */
async function list(data) {
	return await request.get(`/api/organizes/list`, { params: data })
}

export default { list }
