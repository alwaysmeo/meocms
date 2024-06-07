<script setup>
	import { useSidebarStore } from '@stores/sidebarStore'

	const route = useRoute()
	const router = useRouter()

	const sidebarStore = useSidebarStore()

	const sidebar_list = reactive([])

	const expanded_keys = ref()
	const expanded_keys_highlight = ref()

	onMounted(async () => {
		const data = await sidebarStore.get()
		sidebar_list.push(
			...data.map((item) => {
				return { ...item, customIcon: item.icon }
			})
		)
		setExpanded()
	})

	watch(route, () => {
		setExpanded()
	})

	// 默认选中
	function setExpanded() {
		expanded_keys.value = route.matched
			.filter((item) => item.name)
			.map((item) => item.name)
			.slice(0, 2)
		expanded_keys_highlight.value = expanded_keys.value[expanded_keys.value.length - 1]
	}

	function handleMenu(data) {
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
