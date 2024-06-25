import { createI18n } from 'vue-i18n'
import locale from '@opentiny/vue-locale'
import en from './en.json'
import cn from './cn.json'

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
		messages: { cn, en }
	})
