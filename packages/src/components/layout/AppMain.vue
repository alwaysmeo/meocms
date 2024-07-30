<script setup>
	import { isEmpty, isEqual, last } from 'radash'
	import { useTabsListStore } from '@stores/tabsListStore.js'

	const route = useRoute()
	const router = useRouter()
	const tabsListStore = useTabsListStore()

	const tabs_active = ref('')
	const tabs_list = ref()
	const tabs_with_close = computed(() => {
		const keys = Object.keys(tabs_list.value)
		return !isEqual(keys.length, 1) || !isEqual(keys[0], 'home')
	})

	watch(
		route,
		async (to) => {
			const value = to.matched
				.filter((item) => item.name)
				.map((item) => item)
				.slice(0, 2)
			const last_value = last(value)
			tabs_active.value = last_value.name
			await tabsListStore.add({ key: last_value.name, value: last_value.meta.title })
		},
		{ immediate: true }
	)

	onMounted(async () => {
		tabs_list.value = await tabsListStore.get()
	})

	async function closeTabs(event) {
		await tabsListStore.remove(event)
		if (isEmpty(tabs_list.value)) return router.replace({ name: 'home' })
		if (isEqual(route.name, event)) return router.replace({ name: Object.keys(tabs_list.value)[0] })
	}
</script>

<template>
	<div>
		<div class="main-tabs">
			<a-tabs
				v-model:activeKey="tabs_active"
				hideAdd
				type="editable-card"
				@change="router.push({ name: $event })"
				@edit="closeTabs"
			>
				<a-tab-pane v-for="(value, key) in tabs_list" :key="key" :tab="value" :closable="tabs_with_close" />
			</a-tabs>
		</div>
		<div class="main-breadcrumb">
			<a-breadcrumb>
				<a-breadcrumb-item
					v-for="(item, index) in route.matched.filter((item) => item.name)"
					:key="item.name"
					:to="index ? { name: item.name } : {}"
					:class="{ disabled: !index && item.children.length }"
				>
					{{ item.meta.title }}
				</a-breadcrumb-item>
			</a-breadcrumb>
		</div>
		<div class="main-app">
			<div>
				<router-view v-slot="{ Component }">
					<transition appear name="fade-transform" mode="out-in">
						<component :is="Component" />
					</transition>
				</router-view>
			</div>
			<slot name="footer"></slot>
		</div>
	</div>
</template>

<style scoped lang="scss">
	.main-tabs {
		:deep(.ant-tabs-tab-active) {
			background-color: #f5f5f5;
		}
		:deep(.ant-tabs-tab) {
			border: none;
			border-radius: 0 !important;
		}
		:deep(.ant-tabs-nav) {
			margin: 0;
		}
	}
	.main-breadcrumb {
		padding: 10px 20px;
	}
	.main-app {
		height: calc(100vh - 64px - 38px - 42px);
		overflow-y: auto;
		padding: 0 20px 20px 20px;
		> div:first-child {
			min-height: calc(100vh - 64px - 38px - 42px - 50px - 20px);
		}
	}
</style>
