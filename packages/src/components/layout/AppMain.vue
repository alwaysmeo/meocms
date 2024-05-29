<script setup>
	import { isEmpty, isEqual, last } from 'radash'
	import { useTabsListStore } from '@stores/tabsListStore.js'

	const route = useRoute()
	const router = useRouter()
	const tabsListStore = useTabsListStore()

	const tabs_active = ref('')
	const tabs_list = tabsListStore.get()
	const tabs_with_close = computed(() => {
		const keys = Object.keys(tabs_list)
		return !isEqual(keys.length, 1) || !isEqual(keys[0], 'home')
	})

	watch(
		route,
		(to) => {
			const value = to.matched
				.filter((item) => item.name)
				.map((item) => item)
				.slice(0, 2)
			const last_value = last(value)
			tabs_active.value = last_value.name
			tabsListStore.add({ key: last_value.name, value: last_value.meta.title })
		},
		{ immediate: true }
	)

	function closeTabs(event) {
		tabsListStore.remove(event)
		if (isEmpty(tabs_list)) return router.replace({ name: 'home' })
		if (isEqual(route.name, event)) return router.replace({ name: Object.keys(tabs_list)[0] })
	}
</script>

<template>
	<div>
		<div class="main-tabs">
			<tiny-tabs
				v-model="tabs_active"
				:with-close="tabs_with_close"
				overflow-title
				show-more-tabs
				more-show-all
				size="small"
				tab-style="card"
				title-width="120px"
				@close="closeTabs"
				@click="router.push({ name: $event.name })"
			>
				<tiny-tab-item v-for="(value, key) in tabs_list" :key="key" :title="value" :name="key" />
			</tiny-tabs>
		</div>
		<div class="main-app">
			<div class="main-breadcrumb">
				<tiny-breadcrumb>
					<tiny-breadcrumb-item
						v-for="(item, index) in route.matched.filter((item) => item.name)"
						:key="item.name"
						:to="index ? { name: item.name } : {}"
						:label="item.meta.title"
						:class="{ disabled: !index && item.children.length }"
					/>
				</tiny-breadcrumb>
			</div>
			<router-view v-slot="{ Component }">
				<transition appear name="fade-transform" mode="out-in">
					<component :is="Component" />
				</transition>
			</router-view>
		</div>
	</div>
</template>

<style scoped lang="scss">
	.main-tabs {
		:deep(.tiny-tabs__content) {
			margin: 0;
		}
		:deep(.tiny-tabs.tiny-tabs--card > .tiny-tabs__header .tiny-tabs__nav) {
			border-top: none;
			border-left: none;
		}
		:deep(.tiny-tabs.tiny-tabs--small .tiny-tabs__item) {
			border-top: none;
			height: 40px;
			line-height: 1;
		}
	}

	.main-app {
		height: calc(100vh - 60px - 60px - 40px);
		overflow-y: auto;
		padding: 20px;
		.main-breadcrumb {
			margin-bottom: 10px;
			.disabled :deep(.tiny-breadcrumb__inner:hover) {
				cursor: default;
				color: var(--ti-breadcrumb-text-color);
			}
			:deep(.tiny-breadcrumb .tiny-breadcrumb__item) {
				cursor: pointer;
			}
		}
	}
</style>
