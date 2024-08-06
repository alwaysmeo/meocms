<script setup>
	import { message } from 'ant-design-vue'
	import { isEqual, pick } from 'radash'
	import hooks from '@hooks'
	import organizesApi from '@apis/organizes'

	defineOptions({ name: 'SystemOrganizes' })

	const route = useRoute()
	const state = reactive({
		permissions: []
	})

	const mountRef = ref()
	const table = reactive({
		data_screen: [
			{
				key: 'keyword_type,keyword',
				component: 'SelectInput',
				options: {
					'@id': '组织ID',
					'@name': '组织名称'
				}
			}
		],
		columns: [
			{ dataIndex: 'index', title: '序号', width: 120, align: 'center', show: true },
			{ dataIndex: 'id', title: 'ID', width: 260, align: 'center', show: true },
			{ dataIndex: 'name', title: '组织名称', show: true },
			{ dataIndex: 'description', title: '组织描述', ellipsis: true },
			{ dataIndex: 'count', title: '已绑定角色数', width: 130, align: 'center', show: true },
			{ dataIndex: 'show', title: '是否启用', width: 240, align: 'center', show: true },
			{ dataIndex: 'action', title: '操作', width: 160, align: 'center', show: true }
		],
		loading: true,
		data: [],
		page: 1,
		limit: 10,
		total: 0,
		action: {
			detail: {
				name: '详情',
				event: async (record) => {
					detail.data = record
					await roles()
					detail.open = true
				}
			},
			edit: {
				name: '编辑',
				show: () => state.permissions.includes(`${route.name}-update`),
				event: async (record) => {
					form.data = pick(record, ['id', 'name', 'description'])
					form.open = true
				}
			},
			delete: {
				name: '删除',
				show: () => state.permissions.includes(`${route.name}-delete`),
				event: (record) => {
					hooks.useModalConfirm({
						content: h('div', { class: 'meo-modal-body' }, [
							h('p', {}, `确定要删除【${record.name}】组织？`),
							h('p', { class: 'warning' }, `提示：删除前请先解除该组织下绑定的所有角色。`)
						]),
						confirm: async () => {
							if (record.count > 0) return message.warning('请先解除该组织下绑定的所有角色')
							await deleted({ id: record.id })
						}
					})
				}
			}
		}
	})

	const detail = reactive({
		open: false,
		columns: [
			{ dataIndex: 'index', title: '序号', width: 120, align: 'center', show: true },
			{ dataIndex: 'id', title: '角色ID', align: 'center', show: true },
			{ dataIndex: 'name', title: '角色名称', show: true },
			{ dataIndex: 'count', title: '已绑定用户数', width: 120, align: 'center', show: true }
		],
		data: {},
		list: [],
		loading: true,
		page: 1,
		limit: 5,
		total: 0
	})

	const formRef = ref()
	const form = reactive({
		open: false,
		data: {},
		rules: {
			name: [{ required: true, message: '请输入组织名称', trigger: 'blur' }]
		},
		submit: async () => {
			try {
				await formRef.value.validateFields()
				await upsert()
				form.data = {}
				form.open = false
			} catch (error) {
				console.error(error)
				message.warning('请先完善表单信息')
			}
		}
	})

	onMounted(async () => {
		state.permissions = await hooks.usePermissions()
		await list()
	})

	async function list() {
		table.loading = true
		const { code, data } = await organizesApi.list({
			page: table.page,
			limit: table.limit,
			...table.params
		})
		if (isEqual(code, 200)) {
			table.data = data.list
			table.total = data.total
		}
		table.loading = false
	}

	async function upsert() {
		const { code } = await organizesApi.upsert(form.data)
		if (isEqual(code, 200)) {
			message.success(form.data.id ? '修改成功' : '新增成功')
			await list()
		}
	}

	async function deleted({ id }) {
		const { code } = await organizesApi.deleted({ id })
		if (isEqual(code, 200)) {
			message.success('删除成功')
			await list()
		}
	}

	async function changeShow(record) {
		record.show = !record.show
		const { code } = await organizesApi.change.show({ id: record.id, show: record.show ? 1 : 0 })
		if (isEqual(code, 200)) message.success('修改成功')
	}

	async function roles() {
		detail.loading = true
		const { code, data } = await organizesApi.roles({ id: detail.data.id, page: detail.page, limit: detail.limit })
		if (isEqual(code, 200)) {
			detail.list = data.list
			detail.total = data.total
		}
		detail.loading = false
	}

	async function query(params) {
		table.params = params
		await list()
	}
</script>

<template>
	<div>
		<div ref="mountRef"></div>
		<div class="primary-container">
			<div class="primary-header">
				<div>
					<div class="title">组织管理</div>
					<div class="desc">系统组织管理</div>
				</div>
				<a-space>
					<a-button>关联用户</a-button>
					<a-button>排序</a-button>
					<a-button
						type="primary"
						v-if="state.permissions.includes(`${route.name}-create`)"
						@click="table.action.edit.event({})"
					>
						新增组织
					</a-button>
				</a-space>
			</div>
			<meo-table
				v-model:columns="table.columns"
				v-model:page="table.page"
				v-model:limit="table.limit"
				:data-screen="table.data_screen"
				:mount="mountRef"
				:data-source="table.data"
				:loading="table.loading"
				:total="table.total"
				:action="table.action"
				@paginate="list"
				@query="query"
			>
				<template #bodyCell="{ column, record }">
					<template v-if="isEqual(column.dataIndex, 'show')">
						<a-tooltip>
							<template #title>
								{{ record.show ? '点击关闭（已绑定角色不受影响）' : '点击开启' }}
							</template>
							<a-switch :checked="record.show" @change="changeShow(record)" />
						</a-tooltip>
					</template>
				</template>
			</meo-table>
		</div>

		<meo-modal class="detail-modal" v-model:open="detail.open" title="组织详情" cancel="关闭" :confirm="false">
			<div class="tip-title">【{{ detail.data.name }}】已绑定{{ detail.total }}个角色</div>
			<meo-table
				v-model:columns="detail.columns"
				v-model:page="detail.page"
				v-model:limit="detail.limit"
				:columnOption="false"
				:data-source="detail.list"
				:loading="detail.loading"
				:total="detail.total"
				:scroll="{ x: 0 }"
				:pageSizeOptions="['5', '10', '20', '50']"
				size="small"
				@paginate="roles"
			>
				<template #bodyCell="{ column, record }">
					<template v-if="isEqual(column.dataIndex, 'id')">
						{{ record.role_info.id }}
					</template>
					<template v-if="isEqual(column.dataIndex, 'name')">
						{{ record.role_info.name }}
					</template>
					<template v-if="isEqual(column.dataIndex, 'count')">
						{{ record.role_info.count ?? '-' }}
					</template>
				</template>
			</meo-table>
		</meo-modal>

		<meo-modal v-model:open="form.open" :title="form.data.role_id ? '编辑组织' : '新增组织'" :on-confirm="form.submit">
			<a-form ref="formRef" :model="form.data" :rules="form.rules" :label-col="{ span: 6 }" :wrapper-col="{ span: 16 }">
				<a-form-item label="组织名称" name="name">
					<a-input v-model:value="form.data.name" placeholder="请输入组织名称" show-count :maxlength="30" />
				</a-form-item>
				<a-form-item name="description" label="组织描述">
					<a-textarea
						v-model:value="form.data.description"
						:maxlength="200"
						auto-size
						placeholder="请输入组织描述信息"
						show-count
					/>
				</a-form-item>
			</a-form>
		</meo-modal>
	</div>
</template>

<style lang="scss" scoped>
	.detail-modal {
		.tip-title {
			text-align: center;
			color: $color-primary;
			margin-bottom: 10px;
		}
	}
</style>
