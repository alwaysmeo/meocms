<script setup>
	import { pascal } from 'radash'
	import { computedAsync } from '@vueuse/core'
	import stores from '@stores'

	const tabsListStore = stores.useTabsListStore()
	const allow_list = computedAsync(async () => {
		return Object.keys(await tabsListStore.get()).map((item) => pascal(item))
	})
</script>

<template>
	<router-view v-slot="{ Component }">
		<keep-alive :include="allow_list">
			<component :is="Component" />
		</keep-alive>
	</router-view>
</template>

<style scoped lang="scss">
	/** */
</style>
