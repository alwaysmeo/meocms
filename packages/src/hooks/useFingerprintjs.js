import { load } from '@fingerprintjs/botd'

export const useBotd = async () => {
	const botd = await load()
	return botd.detect().bot
}
