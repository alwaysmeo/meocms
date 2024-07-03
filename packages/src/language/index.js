import { createI18n } from 'vue-i18n'
import deDE from './de_DE.json'
import enUS from './en_US.json'
import frFR from './fr_FR.json'
import jaJP from './ja_JP.json'
import koKR from './ko_KR.json'
import ruRU from './ru_RU.json'
import zhCN from './zh_CN.json'
import zhTW from './zh_TW.json'

export default createI18n({
	locale: 'zhCN',
	messages: {
		deDE,
		enUS,
		frFR,
		jaJP,
		koKR,
		ruRU,
		zhCN,
		zhTW
	},
	globalInjection: true,
	legacy: false
})
