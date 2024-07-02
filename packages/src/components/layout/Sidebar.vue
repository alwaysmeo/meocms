<script setup>
	import { useSidebarStore } from '@stores/sidebarStore'
	import { computedAsync } from '@vueuse/core'
	import { isEmpty } from 'radash'

	const route = useRoute()
	const router = useRouter()

	const sidebarStore = useSidebarStore()

	const sidebar_list = computedAsync(async () => {
		return await sidebarStore.getList()
	})

	const openKeys = ref([])
	const selectedKeys = ref([])

	onMounted(async () => {
		openKeys.value = route.matched
			.filter((item) => item.name)
			.map((item) => item.name)
			.slice(0, 2)
		selectedKeys.value = [openKeys.value[openKeys.value.length - 1]]
	})
</script>

<template>
	<div class="sidebar">
		<div class="logo">
			<img src="" alt="logo" />
		</div>
		<a-menu v-model:openKeys="openKeys" v-model:selectedKeys="selectedKeys" mode="inline">
			<template v-for="parent in sidebar_list" :key="parent.code">
				<template v-if="isEmpty(parent.children)">
					<a-menu-item :key="parent.code" @click="router.push({ name: parent.code })">
						<template #icon><component :is="parent.icon" /></template>
						{{ parent.name }}
					</a-menu-item>
				</template>
				<template v-else>
					<a-sub-menu :key="parent.code" :title="parent.name">
						<template #icon><component :is="parent.icon" /></template>
						<a-menu-item
							v-for="child in parent.children"
							:key="child.code"
							:title="child.name"
							@click="router.push({ name: child.code })"
						>
							{{ child.name }}
						</a-menu-item>
					</a-sub-menu>
				</template>
			</template>
		</a-menu>
	</div>
</template>

<style scoped lang="scss">
	.sidebar {
		border-right: 1px solid #eee;
		height: 100vh;
		overflow-y: auto;
		.logo {
			display: flex;
			align-items: center;
			justify-content: center;
			height: 64px;
		}
		:deep(:where(.css-dev-only-do-not-override-19iuou).ant-menu-light.ant-menu-root.ant-menu-inline),
		:deep(:where(.css-dev-only-do-not-override-19iuou).ant-menu-light.ant-menu-root.ant-menu-vertical) {
			border-inline-end: none;
		}
	}
</style>
