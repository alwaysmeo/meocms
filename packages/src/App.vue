<script setup>
	import i18n from '@language'
	import zhCN from 'ant-design-vue/es/locale/zh_CN'
	import zhTW from 'ant-design-vue/es/locale/zh_TW'
	import enUS from 'ant-design-vue/es/locale/en_US'
	import frFR from 'ant-design-vue/es/locale/fr_FR'
	import deDE from 'ant-design-vue/es/locale/de_DE'
	import jaJP from 'ant-design-vue/es/locale/ja_JP'
	import koKR from 'ant-design-vue/es/locale/ko_KR'
	import ruRU from 'ant-design-vue/es/locale/ru_RU'

	const language = {
		zhCN: zhCN,
		zhTW: zhTW,
		enUS: enUS,
		frFR: frFR,
		deDE: deDE,
		jaJP: jaJP,
		koKR: koKR,
		ruRU: ruRU
	}
</script>

<template>
	<a-config-provider :locale="language[i18n.global.locale.value]">
		<router-view />
	</a-config-provider>
</template>

<style lang="scss">
	/** */
</style>
