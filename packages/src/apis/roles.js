'use strict'

import request from '@utils/request'

/**
 * 角色列表
 * @param organize_id * 组织ID
 * @param page * 页码
 * @param limit * 每页条数
 */
async function list(data) {
	return await request.get(`/api/roles/list`, { params: data })
}

/**
 * 新增修改角色
 * @param role_id * 角色ID
 * @param name 角色名称
 * @param show 是否显示
 */
async function upsert(data) {
	return await request.post(`/api/roles/upsert`, data)
}

/**
 * 删除角色
 * @param role_id * 角色ID
 */
async function deleted(data) {
	return await request.post(`/api/roles/delete`, data)
}

/**
 * 获取角色关联的用户
 * @param role_id * 角色ID
 */
async function users(data) {
	return await request.get(`/api/roles/users`, { params: data })
}

export default { list, upsert, deleted, users }
