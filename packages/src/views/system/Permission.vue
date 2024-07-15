<script setup>
	import { message } from 'ant-design-vue'
	import { useModalConfirm } from '@hooks/useModal'
	import { useOrganizesStore } from '@stores/organizesStore'
	import { isEqual } from 'radash'
	import permissionsApi from '@apis/permissions'
	import i18n from '@language'

	defineOptions({ name: 'SystemPermission' })

	const { t } = i18n.global
	const organizesStore = useOrganizesStore()

	const table = reactive({
		columns: [
			{ dataIndex: 'index', title: '序号', width: 120, align: 'center', show: true },
			{ dataIndex: 'id', title: 'ID', align: 'center', show: true },
			{ dataIndex: 'icon', title: '图标', width: 80, align: 'center', show: true },
			{ dataIndex: 'name', title: '权限名称', show: true },
			{ dataIndex: 'description', title: '权限描述', ellipsis: true, show: true },
			{ dataIndex: 'path', title: 'URL', show: true },
			{ dataIndex: 'show', title: '是否显示', width: 240, align: 'center', show: true },
			{ dataIndex: 'action', title: '操作', width: 160, align: 'center', show: true }
		],
		data: [],
		open: false,
		loading: true,
		page: 1,
		limit: 10,
		total: 0,
		action: (key, record) => {
			return {
				edit: () => {
					form.data = { role_id: record.id, name: record.name, show: !!record.show }
					form.open = true
				},
				detail: async () => {},
				delete: () => {
					useModalConfirm({
						content: `确定要删除【${record.name}】角色？`,
						confirm: async () => {}
					})
				}
			}[key]()
		},
		changeShow: async (record) => {
			record.show = !record.show
			// await upsert({ role_id: record.id, show: record.show ? 1 : 0 })
		}
	})

	const form = reactive({
		open: false,
		data: {
			show: true
		},
		rules: {
			name: [{ required: true, message: '请输角色名称', trigger: 'blur' }]
		},
		create: () => {
			form.data = { show: true }
			form.open = true
		},
		submit: async () => {}
	})

	onMounted(async () => {
		await list()
	})

	async function list() {
		table.loading = true
		const organizes = await organizesStore.get()
		const { code, data } = await permissionsApi.list({ organize_id: organizes.checked.id })
		if (isEqual(code, 200)) {
			table.data = data
		}
		table.loading = false
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
					<a-button type="primary" @click="form.create">新增角色</a-button>
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
					<template v-if="isEqual(column.dataIndex, 'icon')">
						<component :is="record.icon" />
					</template>
					<template v-if="isEqual(column.dataIndex, 'show')">
						<a-tooltip>
							<template #title>
								{{ record.show ? '点击隐藏' : '点击开启' }}
							</template>
							<a-switch :checked="record.show" @change="table.changeShow(record)" />
						</a-tooltip>
					</template>
				</template>
			</meo-table>
		</div>
	</div>
</template>

<style lang="scss" scoped>
	/** */
</style>
