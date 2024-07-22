<script setup>
	import { message } from 'ant-design-vue'
	import { useModalConfirm } from '@hooks/useModal'
	import { useOrganizesStore } from '@stores/organizesStore'
	import { isEqual } from 'radash'
	import rolesApi from '@apis/roles'

	defineOptions({ name: 'SystemRole' })

	const organizesStore = useOrganizesStore()

	const table = reactive({
		columns: [
			{ dataIndex: 'index', title: '序号', width: 120, align: 'center', show: true },
			{ dataIndex: 'id', title: 'ID', width: 260, align: 'center', show: true },
			{ dataIndex: 'name', title: '角色名称', show: true },
			{ dataIndex: 'count', title: '当前已绑定用户数', align: 'center', show: true },
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
					form.data = { id: record.id, name: record.name, show: record.show }
					form.open = true
				},
				detail: async () => {
					await users({ role_id: record.id })
					detail.data = record
					detail.open = true
				},
				delete: () => {
					useModalConfirm({
						content: `确定要删除【${record.name}】角色？`,
						confirm: async () => await deleted({ role_id: record.id })
					})
				}
			}[key]()
		},
		changeShow: async (record) => {
			record.show = !record.show
			await upsert({ id: record.id, show: record.show })
		}
	})

	const detail = reactive({
		open: false,
		columns: [
			{ dataIndex: 'index', title: '序号', width: 120, align: 'center', show: true },
			{ dataIndex: 'email', key: 'email', title: '邮箱账号', show: true },
			{ dataIndex: 'nickname', key: 'nickname', title: '用户名', show: true },
			{ dataIndex: 'phone', key: 'phone', title: '联系电话', show: true }
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
		submit: async () => {
			try {
				await formRef.value.validateFields()
				await upsert({ ...form.data, show: form.data.show })
				form.data = { show: true }
				form.open = false
			} catch (error) {
				console.error(error)
				message.warning('请先完善表单信息')
			}
		}
	})

	const organizes = ref()
	onMounted(async () => {
		organizes.value = await organizesStore.get()
		await list()
	})

	async function list() {
		table.loading = true
		const { code, data } = await rolesApi.list({
			organize_id: organizes.value.checked.id,
			page: table.page,
			limit: table.limit
		})
		if (isEqual(code, 200)) {
			table.data = data.list
			table.total = data.total
		}
		table.loading = false
	}

	async function upsert({ id, name, show }) {
		const { code } = await rolesApi.upsert({
			organize_id: organizes.value.checked.id,
			id,
			name,
			show: show ? 1 : 0
		})
		if (isEqual(code, 200)) {
			message.success(id ? '修改成功' : '新增成功')
			await list()
		}
	}

	async function deleted({ role_id }) {
		const { code } = await rolesApi.deleted({ role_id })
		if (isEqual(code, 200)) {
			message.success('删除成功')
			await list()
		}
	}

	async function users({ role_id }) {
		detail.loading = true
		const { code, data } = await rolesApi.users({ role_id, page: detail.page, limit: detail.limit })
		if (isEqual(code, 200)) {
			detail.list = data.list
			detail.total = data.total
		}
		detail.loading = false
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

		<meo-modal class="detail-modal" v-model:open="detail.open" title="角色关联详情" cancel="关闭" :confirm="false">
			<div class="title">【{{ detail.data.name }}】已关联{{ detail.total }}个账号</div>
			<meo-table
				v-model:columns="detail.columns"
				v-model:page="detail.page"
				v-model:limit="detail.limit"
				:dataSource="detail.list"
				:loading="detail.loading"
				:total="detail.total"
				:scroll="{ x: 0 }"
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
		</meo-modal>

		<meo-modal v-model:open="form.open" :title="form.data.role_id ? '编辑角色' : '新增角色'" @confirm="form.submit()">
			<a-form ref="formRef" :model="form.data" :rules="form.rules" :label-col="{ span: 6 }" :wrapper-col="{ span: 16 }">
				<a-form-item label="角色名称" name="name">
					<a-input v-model:value="form.data.name" placeholder="请输入角色名称" show-count :maxlength="30" />
				</a-form-item>
				<a-form-item label="是否启用" name="show">
					<a-switch v-model:checked="form.data.show" />
				</a-form-item>
			</a-form>
		</meo-modal>
	</div>
</template>

<style lang="scss" scoped>
	.detail-modal {
		.title {
			text-align: center;
			color: $color-primary;
			margin-bottom: 10px;
		}
	}
</style>
