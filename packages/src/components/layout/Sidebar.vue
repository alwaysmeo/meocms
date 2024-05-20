<script setup>
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
</script>

<template>
	<div class="sidebar">
		<tiny-tree-menu
			:data="sidebar_list"
			:props="{ children: 'children', label: 'name' }"
			:node-height="50"
			ellipsis
			menu-collapsible
		></tiny-tree-menu>
	</div>
</template>

<style scoped lang="scss">
	.sidebar {
		:deep(.tiny-tree-menu) {
			transition: width 0.3s;
			height: calc(100vh - 60px);
		}
	}
</style>
