'use strict'

import request from '@utils/request'

export default {
	/**
	 * 生成验证码
	 */
	captcha: async function () {
		return await request.post(`/api/common/captcha`)
	}
}
