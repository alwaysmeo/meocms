<script setup>
	import { useSidebarStore } from '@stores/sidebarStore'

	const route = useRoute()
	const router = useRouter()

	const sidebarStore = useSidebarStore()

	const treeMenuRef = ref([])
	const sidebar_list = reactive([])

	onMounted(async () => {
		await sidebar_list.push(...sidebarStore.get())
	})

	const expanded_keys = computed(() => {
		return route.matched
			.filter((item) => item.name)
			.map((item) => item.name)
			.slice(0, 2)
	})
	const expanded_keys_highlight = computed(() => {
		return expanded_keys.value[expanded_keys.value.length - 1]
	})

	function handleMenu(data, node) {
		router.push({ name: data.code })
	}
</script>

<template>
	<div class="sidebar">
		<tiny-tree-menu
			:data="sidebar_list"
			:props="{ children: 'children', label: 'name' }"
			:node-height="50"
			:indent="36"
			:default-expanded-keys="expanded_keys"
			:default-expanded-keys-highlight="expanded_keys_highlight"
			ref="treeMenuRef"
			node-key="code"
			ellipsis
			menu-collapsible
			only-check-children
			@nodeClick="handleMenu"
		/>
	</div>
</template>

<style scoped lang="scss">
	.sidebar {
		:deep(.tiny-input__inner) {
			height: 40px;
		}

		:deep(.tiny-tree-menu) {
			transition: width 0.3s;
			height: calc(100vh - 60px);
		}
	}
</style>
