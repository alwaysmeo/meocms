<script setup>
	import { isEqual } from 'radash'
	import usersApi from '@apis/users'

	defineOptions({ name: 'SystemUser' })

	const table = reactive({
		columns: [
			{ dataIndex: 'index', title: '序号', width: 120, align: 'center' },
			{ dataIndex: 'email', key: 'email', title: '邮箱账号' },
			{ dataIndex: 'nickname', key: 'nickname', title: '用户名' },
			{ dataIndex: 'role', key: 'role', title: '角色组' },
			{ dataIndex: 'status', key: 'status', title: '状态', width: 120, align: 'center' },
			{ dataIndex: 'action', key: 'action', title: '操作', width: 160, align: 'center' }
		],
		data: [],
		loading: true,
		total: 0
	})

	function handleAction(key, record) {
		console.log(key, record)
	}

	onMounted(async () => {
		await list({})
	})

	async function list({ page, limit }) {
		table.loading = true
		const { code, data } = await usersApi.list({ page, limit })
		if (isEqual(code, 200)) {
			table.data = data.list
			table.total = data.total
		}
		table.loading = false
	}
</script>

<template>
	<div>
		<meo-table
			:columns="table.columns"
			:dataSource="table.data"
			:loading="table.loading"
			:total="table.total"
			:action="{ detail: '详情', edit: '编辑', delete: '删除' }"
			@paginate="list"
			@action="handleAction"
		>
			<template #bodyCell="{ column, record }">
				<template v-if="isEqual(column.dataIndex, 'role')">
					{{ record.role ?? '未设置' }}
				</template>
			</template>
		</meo-table>
	</div>
</template>

<style lang="scss" scoped>
	/** */
</style>
