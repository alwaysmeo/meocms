<script setup>
	import { pascal } from 'radash'
	import { useTabsListStore } from '@stores/tabsListStore.js'

	const tabsListStore = useTabsListStore()
	const allow_list = computed(() => {
		return Object.keys(tabsListStore.get()).map((item) => pascal(item))
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
