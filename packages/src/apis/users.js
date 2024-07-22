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
 * 新增修改用户
 * @param organize_id * 组织ID
 * @param ulid 用户ULID
 * @param role_id 角色ID
 * @param nickname 用户名称
 * @param email 邮箱账号
 * @param phone 联系电话
 * @param password 登录密码
 */
async function upsert(data) {
	return await request.post(`/api/users/upsert`, data)
}

/**
 * 获取用户详情
 * @param ulid 用户ULID
 */
async function detail(data) {
	return await request.get(`/api/users/detail`, { params: data })
}

/**
 * 注销删除用户
 * @param ulid 用户ULID
 */
async function deleted(data) {
	return await request.post(`/api/users/delete`, data)
}

/**
 * 获取用户权限列表
 */
async function permissionsList() {
	return await request.get(`/api/users/permissions/list`)
}

export default { list, upsert, detail, deleted, permissionsList }
