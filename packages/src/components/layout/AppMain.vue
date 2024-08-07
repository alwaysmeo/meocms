<script setup>
	import { isEmpty, isEqual, last } from 'radash'
	import stores from '@stores'

	const route = useRoute()
	const router = useRouter()
	const tabsListStore = stores.useTabsListStore()

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

	const openPopover = ref()
	async function handleTabs(type, key) {
		openPopover.value = false
		nextTick(() => {
			openPopover.value = undefined
		})
		setTimeout(() => {
			switch (type) {
				case 'close_other':
					Object.keys(tabs_list.value).forEach(async (key_value) => {
						if (!isEqual(key, key_value)) closeTabs(key_value)
					})
					break
				case 'close_all':
					Object.keys(tabs_list.value).forEach(async (key_value) => {
						closeTabs(key_value)
					})
					break
				default:
					break
			}
		}, 200)
	}

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
				<a-tab-pane v-for="(value, key) in tabs_list" :key="key" :closable="tabs_with_close">
					<template #tab>
						<a-popover
							:open="openPopover"
							:trigger="['contextmenu']"
							placement="bottom"
							overlayClassName="meo-popover-container"
						>
							<div>{{ value }}</div>
							<template #content>
								<a-space direction="vertical">
									<a-button type="text" size="small" block @click="handleTabs('close_other', key)">关闭其他</a-button>
									<a-button type="text" size="small" block @click="handleTabs('close_all', key)">关闭全部</a-button>
								</a-space>
							</template>
						</a-popover>
					</template>
				</a-tab-pane>
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
