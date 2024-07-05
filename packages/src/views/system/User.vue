<script setup>
	import { isEqual } from 'radash'
	import usersApi from '@apis/users'
	import i18n from '@language'

	defineOptions({ name: 'SystemUser' })

	const { t } = i18n.global

	const table = reactive({
		columns: [
			{ dataIndex: 'index', title: t('meo.pages.system.user.table.columns.index'), width: 120, align: 'center', show: true },
			{ dataIndex: 'email', key: 'email', title: t('meo.pages.system.user.table.columns.email'), show: true },
			{ dataIndex: 'nickname', key: 'nickname', title: t('meo.pages.system.user.table.columns.nickname'), show: true },
			{ dataIndex: 'role', key: 'role', title: t('meo.pages.system.user.table.columns.role'), show: true },
			{ dataIndex: 'status', key: 'status', title: t('meo.pages.system.user.table.columns.status'), width: 120, align: 'center', show: true },
			{ dataIndex: 'action', key: 'action', title: t('meo.pages.system.user.table.columns.action'), width: 160, align: 'center', show: true }
		],
		data: [],
		open: false,
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
		<div class="primary-container">
			<div class="primary-header">
				<div>
					<div class="title">{{ $t('meo.pages.system.user.title') }}</div>
					<div class="desc">{{ $t('meo.pages.system.user.desc') }}</div>
				</div>
				<a-space>
					<a-button @click="table.open = true">
						<span>列表筛选</span>
					</a-button>
					<a-button type="primary">Primary Button</a-button>
				</a-space>
			</div>
			<meo-table
				v-model:open="table.open"
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
	</div>
</template>

<style lang="scss" scoped>
	/** */
</style>
