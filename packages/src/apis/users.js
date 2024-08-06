'use strict'

import request from '@utils/request'

export default {
	/**
	 * 获取用户列表
	 * @param organize_id * 组织ID
	 * @param page 页码
	 * @param limit 每页条数
	 * @param search_type 搜索类型【ulid,email,nickname,phone】
	 * @param keyword 搜索关键字
	 */
	list: async function (data) {
		return await request.get(`/api/users/list`, { params: data })
	},

	/**
	 * 新增修改用户
	 * @param organize_id * 组织ID
	 * @param ulid 用户ULID
	 * @param role_id * 角色ID
	 * @param nickname * 用户名称
	 * @param email * 邮箱账号
	 * @param phone 联系电话
	 * @param password * 登录密码
	 */
	upsert: async function (data) {
		return await request.post(`/api/users/upsert`, data)
	},

	/**
	 * 获取用户详情
	 * @param ulid * 用户ULID
	 */
	detail: async function (data) {
		return await request.get(`/api/users/detail`, { params: data })
	},

	/**
	 * 注销删除用户
	 * @param ulid * 用户ULID
	 */
	deleted: async function (data) {
		return await request.post(`/api/users/delete`, data)
	},

	/**
	 * 获取用户拥有的权限列表
	 */
	permissionsList: async function () {
		return await request.get(`/api/users/permissions/list`)
	},

	/**
	 * 获取用户拥有的子权限
	 * @param parent_id		父级权限ID
	 * @param parent_code	父级权限code
	 */
	permissionsChild: async function (data) {
		return await request.get(`/api/users/permissions/child`, { params: data })
	},

	change: {
		/**
		 * 修改用户封禁状态
		 * @param ulid * 用户ULID
		 * @param status * [0封禁, 1正常]
		 */
		status: async function (data) {
			return await request.post(`/api/users/change/status`, data)
		}
	}
}
