import { load } from '@fingerprintjs/botd'

export default {
	useBotd: async () => {
		const botd = await load()
		return botd.detect().bot
	}
}
