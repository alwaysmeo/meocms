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
	}
}
