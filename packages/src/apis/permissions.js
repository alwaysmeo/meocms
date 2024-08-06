'use strict'

import request from '@utils/request'

export default {
	/**
	 * 获取权限列表
	 * @param organize_id * 组织ID
	 */
	list: async function (data) {
		return await request.get(`/api/permissions/list`, { params: data })
	},

	/**
	 * 新增修改权限
	 * @param id 权限ID
	 * @param parent_id 权限父级ID
	 * @param code * 权限唯一标识
	 * @param name * 权限名称
	 * @param description 权限描述信息
	 * @param icon 权限图标
	 * @param path * 权限路径
	 * @param level * 权限级别【1:一级, 2:二级, 3:三级】
	 */
	upsert: async function (data) {
		return await request.post(`/api/permissions/upsert`, data)
	},

	/**
	 * 删除权限
	 * @param id * 权限ID
	 */
	deleted: async function (data) {
		return await request.post(`/api/permissions/delete`, data)
	},

	change: {
		/**
		 * 修改权限启用状态
		 * @param id * 权限ID
		 * @param show * 是否启用
		 */
		show: async function (data) {
			return await request.post(`/api/permissions/change/show`, data)
		}
	}
}
