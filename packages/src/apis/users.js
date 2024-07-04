'use strict'

import request from '@utils/request'

/**
 * 注册
 * @param page 页码
 * @param limit 数量限制
 */
async function list({ page, limit }) {
	return await request.get(`/api/users/list`, { params: { page, limit } })
}

export default { list }
