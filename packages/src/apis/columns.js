'use strict'

import request from '@utils/request'

export default {
	/**
	 * 获取栏目列表
	 * @param organize_id * 组织ID
	 */
	list: async function (data) {
		return await request.get(`/api/columns/list`, { params: data })
	},

	/**
	 * 新增修改栏目
	 * @param id 栏目ID
	 * @param parent_id 栏目父级ID
	 * @param code * 栏目唯一标识
	 * @param name * 栏目名称
	 * @param description 栏目描述信息
	 * @param icon 栏目图标
	 * @param path * 栏目路径
	 * @param level * 栏目级别【1:一级, 2:二级, 3:三级】
	 */
	upsert: async function (data) {
		return await request.post(`/api/columns/upsert`, data)
	},

	/**
	 * 删除栏目
	 * @param id * 栏目ID
	 */
	deleted: async function (data) {
		return await request.post(`/api/columns/delete`, data)
	},

	change: {
		/**
		 * 修改栏目启用状态
		 * @param id * 栏目ID
		 * @param show * 是否启用
		 */
		show: async function (data) {
			return await request.post(`/api/columns/change/show`, data)
		}
	}
}
