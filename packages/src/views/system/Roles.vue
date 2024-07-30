<script setup>
	import { message } from 'ant-design-vue'
	import { useModalConfirm } from '@hooks/useModal'
	import { useOrganizesStore } from '@stores/organizesStore'
	import { isEmpty, isEqual, pick } from 'radash'
	import usePermissions from '@hooks/usePermissions'
	import permissionsApi from '@apis/permissions'
	import rolesApi from '@apis/roles'

	defineOptions({ name: 'SystemRoles' })

	const organizesStore = useOrganizesStore()

	const state = reactive({
		permissions: [],
		organizes: null,
		permission_list: []
	})

	const table = reactive({
		loading: true,
		columns: [
			{ dataIndex: 'index', title: '序号', width: 120, align: 'center', show: true },
			{ dataIndex: 'id', title: 'ID', width: 260, align: 'center', show: true },
			{ dataIndex: 'name', title: '角色名称', show: true },
			{ dataIndex: 'description', title: '角色描述', ellipsis: true },
			{ dataIndex: 'count', title: '已绑定用户数', width: 130, align: 'center', show: true },
			{ dataIndex: 'show', title: '是否启用', width: 240, align: 'center', show: true },
			{ dataIndex: 'action', title: '操作', width: 160, align: 'center', show: true }
		],
		data: [],
		page: 1,
		limit: 10,
		total: 0,
		action: {
			detail: {
				name: '详情',
				event: async (record) => {
					if (isEmpty(state.permission_list)) await permissionList()
					detail.data = record
					await users()
					detail.open = true
				}
			},
			edit: {
				name: '编辑',
				event: async (record) => {
					if (isEmpty(state.permission_list)) await permissionList()
					form.data = pick(record, ['id', 'name', 'description', 'permission_ids'])
					if (isEmpty(record)) form.data.permission_ids = []
					form.open = true
				}
			},
			delete: {
				name: '删除',
				event: (record) => {
					useModalConfirm({
						content: h('div', { class: 'meo-modal-body' }, [
							h('p', {}, `确定要删除【${record.name}】角色？`),
							h('p', { class: 'warning' }, `提示：删除前请先解除该角色下绑定的所有用户。`)
						]),
						confirm: async () => {
							if (record.count > 0) return message.warning('请先解除该角色下绑定的所有用户')
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
			{ dataIndex: 'email', title: '邮箱账号', show: true },
			{ dataIndex: 'nickname', title: '用户名', show: true },
			{ dataIndex: 'phone', title: '联系电话', show: true }
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
			name: [{ required: true, message: '请输入角色名称', trigger: 'blur' }],
			permission_ids: [
				{ required: true, message: '请至少选择一个权限', trigger: 'blur' },
				{
					message: '必须选择【首页】权限',
					trigger: 'blur',
					validator: (rule, value) => {
						if (isEqual(value.checked.filter((item) => isEqual(item, 1)).length, 0)) return Promise.reject()
						return Promise.resolve()
					}
				}
			]
		},
		submit: async () => {
			console.log(form.data)
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
		state.permissions = await usePermissions()
		state.organizes = await organizesStore.get()
		await list()
	})

	async function list() {
		table.loading = true
		const { code, data } = await rolesApi.list({
			organize_id: state.organizes.checked.id,
			page: table.page,
			limit: table.limit
		})
		if (isEqual(code, 200)) {
			table.data = data.list
			table.total = data.total
		}
		table.loading = false
	}

	async function upsert() {
		const { code } = await rolesApi.upsert({
			organize_id: state.organizes.checked.id,
			...form.data,
			permission_ids: JSON.stringify(form.data.permission_ids.checked)
		})
		if (isEqual(code, 200)) {
			message.success(form.data.id ? '修改成功' : '新增成功')
			await list()
		}
	}

	async function deleted({ id }) {
		const { code } = await rolesApi.deleted({ id })
		if (isEqual(code, 200)) {
			message.success('删除成功')
			await list()
		}
	}

	async function changeShow(record) {
		record.show = !record.show
		const { code } = await rolesApi.change.show({ id: record.id, show: record.show ? 1 : 0 })
		if (isEqual(code, 200)) message.success('修改成功')
	}

	async function users() {
		detail.loading = true
		const { code, data } = await rolesApi.users({ id: detail.data.id, page: detail.page, limit: detail.limit })
		if (isEqual(code, 200)) {
			detail.list = data.list
			detail.total = data.total
		}
		detail.loading = false
	}

	async function permissionList() {
		const { code, data } = await permissionsApi.list({ organize_id: state.organizes.checked.id })
		if (isEqual(code, 200)) state.permission_list = data
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
					<a-button type="primary" @click="table.action('edit', {})">新增角色</a-button>
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
					<template v-if="isEqual(column.dataIndex, 'show')">
						<a-tooltip>
							<template #title>
								{{ record.show ? '点击关闭（已绑定用户不受影响）' : '点击开启' }}
							</template>
							<a-switch :checked="record.show" @change="changeShow(record)" />
						</a-tooltip>
					</template>
				</template>
			</meo-table>
		</div>

		<meo-modal class="detail-modal" v-model:open="detail.open" title="角色详情" cancel="关闭" :confirm="false">
			<a-tabs centered>
				<a-tab-pane key="permissions" tab="关联权限">
					<div class="tree-container">
						<a-tree
							v-model:checkedKeys="detail.permissions_ids"
							:tree-data="state.permission_list"
							:fieldNames="{ children: 'children', title: 'name', key: 'id' }"
						>
							<template #title="item">
								<div class="name" :class="{ active: detail.data.permission_ids.includes(item.id) }">
									<span class="label">●</span>
									<span>{{ item.name }}</span>
								</div>
							</template>
						</a-tree>
					</div>
				</a-tab-pane>
				<a-tab-pane key="users" tab="关联用户">
					<div class="tip-title">【{{ detail.data.name }}】已关联{{ detail.total }}个账号</div>
					<meo-table
						v-model:columns="detail.columns"
						v-model:page="detail.page"
						v-model:limit="detail.limit"
						:columnOption="false"
						:dataSource="detail.list"
						:loading="detail.loading"
						:total="detail.total"
						:scroll="{ x: 0 }"
						:pageSizeOptions="['5', '10', '20', '50']"
						size="small"
						@paginate="users"
					>
						<template #bodyCell="{ column, record }">
							<template v-if="isEqual(column.dataIndex, 'email')">
								{{ record.user_info.email }}
							</template>
							<template v-if="isEqual(column.dataIndex, 'nickname')">
								{{ record.user_info.nickname }}
							</template>
							<template v-if="isEqual(column.dataIndex, 'phone')">
								{{ record.user_info.phone ?? '-' }}
							</template>
						</template>
					</meo-table>
				</a-tab-pane>
			</a-tabs>
		</meo-modal>

		<meo-modal v-model:open="form.open" :title="form.data.role_id ? '编辑角色' : '新增角色'" :on-confirm="form.submit">
			<a-form ref="formRef" :model="form.data" :rules="form.rules" :label-col="{ span: 6 }" :wrapper-col="{ span: 16 }">
				<a-form-item label="角色名称" name="name">
					<a-input v-model:value="form.data.name" placeholder="请输入角色名称" show-count :maxlength="30" />
				</a-form-item>
				<a-form-item name="description" label="角色描述">
					<a-textarea v-model:value="form.data.description" :maxlength="200" auto-size placeholder="请输入角色描述信息" show-count />
				</a-form-item>
				<a-form-item label="角色权限" name="permission_ids" validateFirst>
					<div class="permissions-container">
						<a-tree
							checkable
							checkStrictly
							v-model:checkedKeys="form.data.permission_ids"
							:tree-data="state.permission_list"
							:fieldNames="{ children: 'children', title: 'name', key: 'id' }"
						/>
					</div>
				</a-form-item>
			</a-form>
		</meo-modal>
	</div>
</template>

<style lang="scss" scoped>
	.detail-modal {
		.tree-container {
			max-height: 400px;
			overflow-y: auto;
			.label {
				padding-right: 6px;
			}
			.name {
				color: $color-gray;
			}
			.active {
				color: $color-primary;
			}
		}
		.tip-title {
			text-align: center;
			color: $color-primary;
			margin-bottom: 10px;
		}
	}
	.permissions-container {
		max-height: 320px;
		overflow-y: auto;
	}
</style>
