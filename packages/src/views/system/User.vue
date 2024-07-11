<script setup>
	import { isEqual } from 'radash'
	import usersApi from '@apis/users'
	import i18n from '@language'

	defineOptions({ name: 'SystemUser' })

	const { t } = i18n.global

	const table = reactive({
		columns: [
			{ dataIndex: 'index', title: t('meo.pages.system.user.table.columns.index'), width: 120, align: 'center', show: true },
			{ dataIndex: 'ulid', key: 'ulid', title: t('meo.pages.system.user.table.columns.id'), width: 260 },
			{ dataIndex: 'picture_info', key: 'picture_info', title: t('meo.pages.system.user.table.columns.picture_info'), width: 120, align: 'center', show: true },
			{ dataIndex: 'email', key: 'email', title: t('meo.pages.system.user.table.columns.email'), show: true },
			{ dataIndex: 'phone', key: 'phone', title: t('meo.pages.system.user.table.columns.phone'), width: 150, align: 'center' },
			{ dataIndex: 'nickname', key: 'nickname', title: t('meo.pages.system.user.table.columns.nickname'), show: true },
			{ dataIndex: 'role', key: 'role', title: t('meo.pages.system.user.table.columns.role'), align: 'center', show: true },
			{ dataIndex: 'created_at', key: 'created_at', title: t('meo.pages.system.user.table.columns.created_at'), width: 170, align: 'center' },
			{ dataIndex: 'last_login_at', key: 'last_login_at', title: t('meo.pages.system.user.table.columns.last_login_at'), width: 170, align: 'center' },
			{ dataIndex: 'status', key: 'status', title: t('meo.pages.system.user.table.columns.status'), width: 120, align: 'center', show: true },
			{ dataIndex: 'action', key: 'action', title: t('meo.pages.system.user.table.columns.action'), width: 160, align: 'center', show: true }
		],
		data: [],
		open: false,
		loading: true,
		page: 1,
		limit: 10,
		total: 0,
		action: (key, record) => {
			return {
				detail: () => {
					detail.open = true
					detail.data = record
				}
			}[key]()
		}
	})

	const detail = reactive({
		open: false
	})

	onMounted(async () => {
		await list({ page: table.page, limit: table.limit })
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
						<span>{{ $t('meo.components.common.table.list_filtering') }}</span>
					</a-button>
					<a-button type="primary">Primary Button</a-button>
				</a-space>
			</div>
			<meo-table
				v-model:open="table.open"
				v-model:columns="table.columns"
				v-model:page="table.page"
				v-model:limit="table.limit"
				:dataSource="table.data"
				:loading="table.loading"
				:total="table.total"
				:action="{
					detail: $t('meo.components.common.table.action.detail'),
					edit: $t('meo.components.common.table.action.edit'),
					delete: $t('meo.components.common.table.action.delete')
				}"
				@paginate="list"
				@action="table.action"
			>
				<template #bodyCell="{ column, record }">
					<template v-if="isEqual(column.dataIndex, 'picture_info')">
						<a-avatar :size="50" :src="record?.picture_info?.url">
							<template #icon><ant-user-outlined /></template>
						</a-avatar>
					</template>
					<template v-if="isEqual(column.dataIndex, 'phone')">
						{{ record.phone ?? '-' }}
					</template>
					<template v-if="isEqual(column.dataIndex, 'role')"> 【{{ record.role_info.name }}】 </template>
					<template v-if="isEqual(column.dataIndex, 'status')">
						{{ ['封禁', '正常'][record.status] }}
					</template>
				</template>
			</meo-table>
		</div>

		<meo-modal v-model:open="detail.open" title="用户详情" cancel="关闭" :confirm="false">
			<div>123</div>
		</meo-modal>
	</div>
</template>

<style lang="scss" scoped>
	/** */
</style>
