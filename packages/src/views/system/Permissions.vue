<script setup>
	import { message } from 'ant-design-vue'
	import { useModalConfirm } from '@hooks/useModal'
	import { useOrganizesStore } from '@stores/organizesStore'
	import { isEqual, pick } from 'radash'
	import permissionsApi from '@apis/permissions'

	defineOptions({ name: 'SystemPermissions' })

	const organizesStore = useOrganizesStore()

	const state = reactive({
		organizes: null
	})

	const table = reactive({
		loading: true,
		columns: [
			{ dataIndex: 'index', title: '序号', width: 120, align: 'center', show: true },
			{ dataIndex: 'icon', title: '图标', width: 120, align: 'center', show: true },
			{ dataIndex: 'code', title: '唯一标识', align: 'center', show: true },
			{ dataIndex: 'name', title: '权限名称', show: true },
			{ dataIndex: 'type', title: '权限类型', width: 120, show: true },
			{ dataIndex: 'description', title: '权限描述', ellipsis: true },
			{ dataIndex: 'path', title: 'URL', show: true },
			{ dataIndex: 'show', title: '是否启用', width: 120, align: 'center', show: true },
			{ dataIndex: 'action', title: '操作', width: 160, align: 'center', show: true }
		],
		data: [],
		page: 1,
		limit: 10,
		total: 0,
		action: {
			create: {
				name: '新增',
				event: async (record) => {
					form.data = {
						parent_id: record.id,
						code: `${record.code}-`,
						level: record.level + 1
					}
					form.open = true
				}
			},
			edit: {
				name: '编辑',
				event: (record) => {
					form.data = pick(record, ['id', 'parent_id', 'code', 'icon', 'name', 'description', 'path', 'level', 'type'])
					form.data.level = record.level ?? 0
					form.open = true
				}
			},
			delete: {
				name: '删除',
				event: (record) => {
					useModalConfirm({
						content: `确定要删除【${record.name}】权限？`,
						confirm: async () => await deleted({ id: record.id })
					})
				}
			}
		}
	})

	const formRef = ref()
	const form = reactive({
		open: false,
		data: {},
		role_list: [],
		rules: {
			code: [{ required: true, message: '请输入权限唯一标识', trigger: 'blur' }],
			name: [{ required: true, message: '请输入权限名称', trigger: 'blur' }],
			type: [{ required: true, message: '请选择权限类型', trigger: 'blur' }]
		},
		submit: async () => {
			try {
				await formRef.value?.validateFields()
				await upsert()
				form.open = false
				form.data = {}
			} catch (error) {
				console.error(error)
				message.warning('请先完善表单信息')
			}
		}
	})

	onMounted(async () => {
		state.organizes = await organizesStore.get()
		await list()
	})

	async function list() {
		table.loading = true
		const { code, data } = await permissionsApi.list({ organize_id: state.organizes.checked.id })
		if (isEqual(code, 200)) {
			table.data = data
		}
		table.loading = false
	}

	async function upsert() {
		const { code } = await permissionsApi.upsert(form.data)
		if (isEqual(code, 200)) {
			message.success(form.data.id ? '修改成功' : '新增成功')
			await list()
		}
	}

	async function deleted({ id }) {
		const { code } = await permissionsApi.deleted({ id })
		if (isEqual(code, 200)) {
			message.success('删除成功')
			await list()
		}
	}

	async function changeShow(record) {
		record.show = !record.show
		const { code } = await permissionsApi.change.show({ id: record.id, show: record.show ? 1 : 0 })
		if (isEqual(code, 200)) message.success('修改成功')
	}
</script>

<template>
	<div>
		<div class="primary-container">
			<div class="primary-header">
				<div>
					<div class="title">权限管理</div>
					<div class="desc">系统权限管理</div>
				</div>
				<a-space>
					<a-button type="primary" @click="table.action('edit', {})">新增权限</a-button>
				</a-space>
			</div>
			<meo-table
				v-model:columns="table.columns"
				v-model:page="table.page"
				v-model:limit="table.limit"
				:dataSource="table.data"
				:loading="table.loading"
				:total="table.total"
				:action="table.action"
				@paginate="list"
			>
				<template #bodyCell="{ column, record }">
					<template v-if="isEqual(column.dataIndex, 'icon')">
						<component :is="record.icon" />
					</template>
					<template v-if="isEqual(column.dataIndex, 'type')">
						<a-tag color="green" v-if="isEqual(record.type, 1)">菜单目录</a-tag>
						<a-tag color="blue" v-if="isEqual(record.type, 2)">页面按钮</a-tag>
					</template>
					<template v-if="isEqual(column.dataIndex, 'show')">
						<a-tooltip v-if="!isEqual(record.id, 1)">
							<template #title>
								{{ record.show ? '点击关闭' : '点击开启' }}
							</template>
							<a-switch :checked="record.show" @change="changeShow(record)" />
						</a-tooltip>
					</template>
				</template>
			</meo-table>
		</div>

		<meo-modal v-model:open="form.open" :title="form.data.ulid ? '修改权限' : '新增权限'" :on-confirm="form.submit">
			<div>
				<a-form
					ref="formRef"
					:model="form.data"
					:rules="form.rules"
					:label-col="{ span: 6 }"
					:wrapper-col="{ span: 16 }"
				>
					<a-form-item name="code" label="唯一标识">
						<a-input v-model:value="form.data.code" :maxlength="100" placeholder="请输入权限唯一标识" show-count />
					</a-form-item>
					<a-form-item name="icon" label="图标">
						<a-input v-model:value="form.data.icon" :maxlength="100" placeholder="请输入图标码" show-count />
					</a-form-item>
					<a-form-item name="name" label="权限名称">
						<a-input v-model:value="form.data.name" :maxlength="80" placeholder="请输入权限名称" show-count />
					</a-form-item>
					<a-form-item name="type" label="权限类型">
						<a-select v-model:value="form.data.type" placeholder="请选择权限类型">
							<a-select-option :value="1">菜单目录</a-select-option>
							<a-select-option :value="2">页面按钮</a-select-option>
						</a-select>
					</a-form-item>
					<a-form-item name="description" label="描述信息">
						<a-textarea
							v-model:value="form.data.description"
							:maxlength="200"
							:auto-size="{ minRows: 2, maxRows: 5 }"
							placeholder="请输入权限描述信息"
							show-count
						/>
					</a-form-item>
					<a-form-item name="path" label="URL">
						<a-input v-model:value="form.data.path" :maxlength="250" placeholder="请输入权限 URL" show-count />
					</a-form-item>
				</a-form>
			</div>
		</meo-modal>
	</div>
</template>

<style lang="scss" scoped>
	/** */
</style>
