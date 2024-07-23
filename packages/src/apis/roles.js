'use strict'

import request from '@utils/request'

export default {
	/**
	 * 角色列表
	 * @param organize_id * 组织ID
	 * @param page * 页码
	 * @param limit * 每页条数
	 */
	list: async function (data) {
		return await request.get(`/api/roles/list`, { params: data })
	},

	/**
	 * 新增修改角色
	 * @param organize_id * 组织ID
	 * @param id 角色ID
	 * @param name 角色名称
	 * @param description 角色描述
	 * @param permissions * 权限IDS
	 */
	upsert: async function (data) {
		return await request.post(`/api/roles/upsert`, data)
	},

	/**
	 * 删除角色
	 * @param id * 角色ID
	 */
	deleted: async function (data) {
		return await request.post(`/api/roles/delete`, data)
	},

	/**
	 * 获取角色关联的用户
	 * @param id * 角色ID
	 */
	users: async function (data) {
		return await request.get(`/api/roles/users`, { params: data })
	},

	change: {
		/**
		 * 修改角色启用状态
		 * @param id * 角色ID
		 * @param show * 是否启用
		 */
		show: async function (data) {
			return await request.post(`/api/roles/change/show`, data)
		}
	}
}
