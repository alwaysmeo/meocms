<script setup>
	import { last } from 'radash'
	import { useTabsListStore } from '@stores/tabsListStore.js'

	const route = useRoute()
	const router = useRouter()
	const tabsListStore = useTabsListStore()

	const tabs_active = ref('')
	const tabs_list = tabsListStore.get()

	watch(
		route,
		(to, from) => {
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
</script>

<template>
	<div>
		<div class="main-tabs">
			<tiny-tabs
				v-model="tabs_active"
				:with-close="true"
				tab-style="card"
				@click="router.push({ name: $event.name })"
				@close="tabsListStore.remove($event)"
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
					<keep-alive>
						<component :is="Component" />
					</keep-alive>
				</transition>
			</router-view>
		</div>
	</div>
</template>

<style scoped lang="scss">
	.main-app {
		height: calc(100vh - 60px - 60px);
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
