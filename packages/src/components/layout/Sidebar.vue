<script setup>
	const route = useRoute()
	const router = useRouter()

	const treeMenuRef = ref([])
	const sidebar_list = reactive([
		{
			id: 1,
			code: 'home',
			customIcon: 'TinyIconPublicHome',
			name: '首页'
		},
		{
			id: 2,
			code: 'system',
			customIcon: 'TinyIconDataSource',
			name: '系统管理',
			children: [
				{
					id: 3,
					code: 'system-user',
					name: '用户管理'
				},
				{
					id: 4,
					code: 'system-role',
					name: '角色管理'
				},
				{
					id: 5,
					code: 'system-permission',
					name: '权限管理'
				}
			]
		}
	])

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
