<script setup>
	import { message } from 'ant-design-vue'
	import { isEqual } from 'radash'
	import rolesApi from '@apis/roles'
	import i18n from '@language'

	defineOptions({ name: 'SystemRole' })

	const { t } = i18n.global

	const table = reactive({
		columns: [
			{ dataIndex: 'index', title: '序号', width: 120, align: 'center', show: true },
			{ dataIndex: 'id', key: 'id', title: 'ID', width: 260, align: 'center', show: true },
			{ dataIndex: 'name', key: 'name', title: '角色名称', show: true },
			{ dataIndex: 'count', key: 'count', title: '当前已绑定用户数', align: 'center', show: true },
			{ dataIndex: 'show', key: 'show', title: '是否显示', width: 240, align: 'center', show: true },
			{ dataIndex: 'action', key: 'action', title: '操作', width: 160, align: 'center', show: true }
		],
		data: [],
		open: false,
		loading: true,
		page: 1,
		limit: 10,
		total: 0,
		action: (key, record) => {
			console.log(key, record)
			return {
				detail: () => {
					detail.open = true
				}
			}[key]()
		},
		changeShow: async (record) => {
			record.show = !record.show
			await upsert({ role_id: record.id, show: record.show ? 1 : 0 })
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
		const { code, data } = await rolesApi.list({ page, limit })
		if (isEqual(code, 200)) {
			table.data = data.list
			table.total = data.total
		}
		table.loading = false
	}

	async function upsert({ role_id, name, show }) {
		const { code } = await rolesApi.upsert({ role_id, name, show })
		if (isEqual(code, 200)) {
			message.success(role_id ? '修改成功' : '新增成功')
			await list()
		}
	}
</script>

<template>
	<div>
		<div class="primary-container">
			<div class="primary-header">
				<div>
					<div class="title">角色管理</div>
					<div class="desc">系统角色管理</div>
				</div>
				<a-space>
					<a-button @click="table.open = true">
						<span>{{ $t('meo.components.common.table.list_filtering') }}</span>
					</a-button>
					<a-button type="primary">新增角色</a-button>
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
					<template v-if="isEqual(column.dataIndex, 'show')">
						<a-tooltip>
							<template #title>
								{{ record.show ? '点击隐藏（已绑定用户不受影响）' : '点击开启' }}
							</template>
							<a-switch :checked="record.show" @change="table.changeShow(record)" />
						</a-tooltip>
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
