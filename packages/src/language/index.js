import { createI18n } from 'vue-i18n'
import locale from '@opentiny/vue-locale'
import enUS from './enUS.json'
import zhCN from './zhCN.json'

export default locale.initI18n({
	i18n: { locale: 'enUS' },
	createI18n: ({ locale, messages }) => {
		return createI18n({
			locale,
			messages,
			legacy: false
		})
	},
	messages: { zhCN, enUS }
})
