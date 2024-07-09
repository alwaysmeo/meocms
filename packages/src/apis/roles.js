'use strict'

import request from '@utils/request'

/**
 * 角色列表
 * @param page 页码
 * @param limit 每页条数
 */
async function list(data) {
	return await request.get(`/api/roles/list`, { params: data })
}

/**
 * 新增修改角色
 * @param role_id 角色id
 * @param name 角色名称
 * @param show 是否显示
 */
async function upsert(data) {
	return await request.post(`/api/roles/upsert`, data)
}

export default { list, upsert }
