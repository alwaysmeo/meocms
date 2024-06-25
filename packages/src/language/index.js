import { createI18n } from 'vue-i18n'
import locale from '@opentiny/vue-locale'
import enUS from './en-US.json'
import zhCN from './zh-CN.json'

export default (i18n) =>
	locale.initI18n({
		i18n,
		createI18n: ({ locale, messages }) => {
			return createI18n({
				locale,
				messages,
				legacy: false
			})
		},
		messages: { zhCN, enUS }
	})
