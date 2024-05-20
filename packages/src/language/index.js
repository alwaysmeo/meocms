import { createI18n } from 'vue-i18n'
import locale from '@opentiny/vue-locale'
import enUS from './en-US.json'
import zhCN from './zh-CN.json'

export default (i18n) => {
	return locale.initI18n({
		i18n,
		createI18n: ({ locale, messages }) => {
			return createI18n({
				locale,
				messages,
				legacy: false
			})
		},
		messages: {
			zhCN: {
				test: '中文',
				meo: zhCN
			},
			enUS: {
				test: 'English',
				meo: enUS
			}
		}
	})
}
