'use strict'

import request from '@utils/request'

/**
 * 生成验证码
 */
async function captcha() {
	return await request.post(`/api/common/captcha`)
}

export default { captcha }
