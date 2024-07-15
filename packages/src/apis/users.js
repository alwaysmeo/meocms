'use strict'

import request from '@utils/request'

/**
 * 获取用户列表
 * @param page * 页码
 * @param limit * 每页条数
 * @param search_type 搜索类型【ulid,email,nickname,phone】
 * @param keyword 搜索关键字
 */
async function list(data) {
	return await request.get(`/api/users/list`, { params: data })
}

/**
 * 获取用户权限列表
 */
async function permissionsList() {
	return await request.get(`/api/users/permissions/list`)
}

export default { list, permissionsList }
