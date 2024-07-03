import { createI18n } from 'vue-i18n'
import enUS from './en_US.json'
import zhCN from './zh_CN.json'

export default createI18n({
	locale: 'enUS',
	messages: {
		zhCN,
		enUS
	},
	globalInjection: true,
	legacy: false
})
