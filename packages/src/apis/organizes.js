'use strict'

import request from '@utils/request'

export default {
	/**
	 * 组织列表
	 * @param page 页码
	 * @param limit 每页条数
	 */
	list: async function (data) {
		return await request.get(`/api/organizes/list`, { params: data })
	},

	/**
	 * 新增修改组织
	 * @param id 组织ID
	 * @param name * 组织名称
	 * @param description 组织描述
	 */
	upsert: async function (data) {
		return await request.post(`/api/organizes/upsert`, data)
	},

	/**
	 * 删除组织
	 * @param id * 组织ID
	 */
	deleted: async function (data) {
		return await request.post(`/api/organizes/delete`, data)
	},

	/**
	 * 获取组织关联的角色
	 * @param id * 组织ID
	 */
	roles: async function (data) {
		return await request.get(`/api/organizes/roles`, { params: data })
	},

	change: {
		/**
		 * 修改组织启用状态
		 * @param id * 组织ID
		 * @param show * 是否启用
		 */
		show: async function (data) {
			return await request.post(`/api/organizes/change/show`, data)
		}
	}
}
