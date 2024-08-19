import { load } from '@fingerprintjs/botd'

export default {
	/** 验证是否是机器人 */
	useBotd: async () => {
		const botd = await load()
		return botd.detect().bot
	}
}
