<script setup>
	import { message } from 'ant-design-vue'
	import { isEmpty, isEqual, pick } from 'radash'
	import { useOrganizesStore } from '@stores/organizesStore'
	import { usePermissions, useModalConfirm } from '@hooks'
	import rexExp from '@utils/rexExp'
	import usersApi from '@apis/users'
	import rolesApi from '@apis/roles'
	import i18n from '@language'

	defineOptions({ name: 'SystemUsers' })

	const { t } = i18n.global
	const route = useRoute()
	const organizesStore = useOrganizesStore()

	const state = reactive({
		permissions: [],
		organizes: null
	})

	const table = reactive({
		controller: [
			{
				key: 'keyword_type,keyword',
				component: 'SelectInput',
				placeholder_type: '请选择',
				options: {
					'@ulid': '用户ID',
					'@email': '邮箱账号',
					'@nickname': '用户名',
					'@phone': '联系电话'
				}
			},
			{
				key: 'created_at',
				component: 'DateRangePicker',
				name: '创建时间'
			},
			{
				key: 'last_login_at',
				component: 'DateRangePicker',
				name: '最近登录时间'
			},
			{
				key: 'status',
				component: 'Select',
				name: '账号状态',
				placeholder: '请选择账号状态',
				options: {
					'@null': '全部',
					'@1': '正常',
					'@0': '已封禁'
				}
			}
		],
		columns: [
			{
				dataIndex: 'index',
				title: t('meo.pages.system.user.table.columns.index'),
				width: 120,
				align: 'center',
				show: true
			},
			{ dataIndex: 'ulid', title: t('meo.pages.system.user.table.columns.id'), width: 260 },
			{
				dataIndex: 'picture',
				title: t('meo.pages.system.user.table.columns.picture'),
				width: 120,
				align: 'center',
				show: true
			},
			{ dataIndex: 'email', title: t('meo.pages.system.user.table.columns.email'), show: true },
			{ dataIndex: 'phone', title: t('meo.pages.system.user.table.columns.phone'), width: 150, align: 'center' },
			{ dataIndex: 'nickname', title: t('meo.pages.system.user.table.columns.nickname'), show: true },
			{ dataIndex: 'role', title: t('meo.pages.system.user.table.columns.role'), align: 'center', show: true },
			{
				dataIndex: 'created_at',
				title: t('meo.pages.system.user.table.columns.created_at'),
				width: 170,
				align: 'center'
			},
			{
				dataIndex: 'last_login_at',
				title: '最近登录时间',
				width: 170,
				align: 'center'
			},
			{
				dataIndex: 'status',
				title: t('meo.pages.system.user.table.columns.status'),
				width: 120,
				align: 'center',
				show: true
			},
			{
				dataIndex: 'action',
				title: t('meo.pages.system.user.table.columns.action'),
				width: 160,
				align: 'center',
				show: true
			}
		],
		data: [],
		loading: true,
		page: 1,
		limit: 10,
		total: 0,
		action: {
			detail: {
				name: '详情',
				event: async (record) => {
					const { code, data } = await usersApi.detail({ ulid: record.ulid })
					if (isEqual(code, 200)) {
						detail.select = 0 // 组织列表的 index 索引
						detail.data = data
						detail.open = true
					}
				}
			},
			edit: {
				name: '编辑',
				show: () => state.permissions.includes(`${route.name}-update`),
				event: async (record) => {
					if (isEmpty(form.role_list)) {
						const { code, data } = await rolesApi.list({ organize_id: state.organizes.checked.id })
						if (isEqual(code, 200)) form.role_list = data.list
					}
					form.data = pick(record, ['ulid', 'nickname', 'email', 'phone'])
					form.data.role_id = record.role_info?.id
					form.open = true
				}
			},
			banned: {
				name: '封禁',
				show: (record) => isEqual(record.status, 1) && state.permissions.includes(`${route.name}-banned`),
				event: (record) => {
					useModalConfirm({
						title: '提示',
						content: h('div', { class: 'meo-modal-body' }, [
							h('p', {}, `是否封禁【${record.nickname}】用户？`),
							h('p', { class: 'warning' }, `注意：确认封禁后该用户将无法登录账号，请谨慎操作！`)
						]),
						confirm: async () => {
							await changeStatus({ ulid: record.ulid, status: 0 })
						}
					})
				}
			},
			unseal: {
				name: '解封',
				show: (record) => isEqual(record.status, 0) && state.permissions.includes(`${route.name}-unseal`),
				event: (record) => {
					useModalConfirm({
						title: '提示',
						content: h('div', { class: 'meo-modal-body' }, [
							h('p', {}, `是否解封【${record.nickname}】用户？`),
							h('p', { class: 'warning' }, `注意：确认解封后该用户即可正常登录。`)
						]),
						confirm: async () => {
							await changeStatus({ ulid: record.ulid, status: 1 })
						}
					})
				}
			},
			delete: {
				name: '删除',
				show: () => state.permissions.includes(`${route.name}-update`),
				event: (record) => {
					useModalConfirm({
						title: '提示',
						content: h('div', { class: 'meo-modal-body' }, [
							h('p', {}, `是否删除【${record.nickname}】用户？`),
							h('p', { class: 'warning' }, `注意：确认删除后该用户将无法登录账号。\n该操作无法撤销恢复，请谨慎操作！`)
						]),
						confirm: async () => {
							const { code } = await rolesApi.deleted({ ulid: record.ulid })
							if (isEqual(code, 200)) message.success('删除成功')
						}
					})
				}
			}
		}
	})

	const detail = reactive({
		open: false,
		select: null,
		data: {}
	})

	const formRef = ref()
	const form = reactive({
		open: false,
		data: {},
		role_list: [],
		rules: {
			nickname: [{ required: true, message: '请输入用户名', trigger: 'blur' }],
			email: [
				{ required: true, message: '请输入邮箱账号', trigger: 'blur' },
				{ pattern: rexExp.email, message: '邮箱格式不正确', trigger: 'change' }
			],
			phone: [{ pattern: rexExp.phone, message: '手机号格式不正确', trigger: 'change' }],
			password: [
				{ required: true, message: '请输入密码', trigger: 'blur' },
				{ min: 6, max: 20, message: '密码长度应在6-20位之间', trigger: 'blur' },
				{ pattern: rexExp.password, message: '密码只能包含字母或数字', trigger: 'change' }
			],
			role_id: [{ required: true, message: '请选择角色组', trigger: 'blur' }]
		},
		submit: async () => {
			try {
				await formRef.value?.validateFields()
				await upsert()
			} catch (error) {
				console.error(error)
				message.warning('请先完善表单信息')
			}
		}
	})

	const mountRef = ref()

	onMounted(async () => {
		state.permissions = await usePermissions()
		state.organizes = await organizesStore.get()
		await list()
	})

	async function list() {
		table.loading = true
		const { code, data } = await usersApi.list({
			organize_id: state.organizes.checked.id,
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
		const { code } = await usersApi.upsert({
			organize_id: state.organizes.checked.id,
			...form.data
		})
		if (isEqual(code, 200)) {
			await list()
			form.open = false
			form.data = {}
		}
	}

	async function changeStatus({ ulid, status }) {
		const { code } = await usersApi.change.status({ ulid, status })
		if (isEqual(code, 200)) {
			message.success(['封禁成功', '解封成功'][status])
			await list()
		}
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
					<div class="title">{{ $t('meo.pages.system.user.title') }}</div>
					<div class="desc">{{ $t('meo.pages.system.user.desc') }}</div>
				</div>
				<a-space>
					<a-button
						type="primary"
						v-if="state.permissions.includes(`${route.name}-create`)"
						@click="table.action.edit.event({})"
					>
						新增用户
					</a-button>
				</a-space>
			</div>
			<meo-table
				v-model:columns="table.columns"
				v-model:page="table.page"
				v-model:limit="table.limit"
				:controller="table.controller"
				:mount="mountRef"
				:dataSource="table.data"
				:loading="table.loading"
				:total="table.total"
				:action="table.action"
				@paginate="list"
				@query="query"
			>
				<template #bodyCell="{ column, record }">
					<template v-if="isEqual(column.dataIndex, 'picture')">
						<a-avatar :size="50" :src="record?.picture?.url">
							<template #icon><ant-user-outlined /></template>
						</a-avatar>
					</template>
					<template v-if="isEqual(column.dataIndex, 'phone')">
						{{ record.phone ?? '-' }}
					</template>
					<template v-if="isEqual(column.dataIndex, 'role')">【{{ record.role_info.name }}】</template>
					<template v-if="isEqual(column.dataIndex, 'status')">
						<span :class="['color-error', 'color-success'][record.status]">
							{{ ['封禁', '正常'][record.status] }}
						</span>
					</template>
				</template>
			</meo-table>
		</div>

		<meo-modal v-model:open="form.open" :title="form.data.ulid ? '修改用户' : '新增用户'" :on-confirm="form.submit">
			<div>
				<a-form
					ref="formRef"
					:model="form.data"
					:rules="form.rules"
					:label-col="{ span: 6 }"
					:wrapper-col="{ span: 16 }"
				>
					<a-form-item name="nickname" label="用户名">
						<a-input v-model:value="form.data.nickname" :maxlength="30" placeholder="请输入用户名" show-count />
					</a-form-item>
					<a-form-item name="role_id" label="角色组">
						<a-select v-model:value="form.data.role_id" placeholder="请选择角色组">
							<a-select-option v-for="item in form.role_list" :key="item.id" :value="item.id">
								{{ item.name }}
							</a-select-option>
						</a-select>
					</a-form-item>
					<a-form-item name="email" label="邮箱账号">
						<a-input
							v-model:value="form.data.email"
							:maxlength="60"
							placeholder="请输入邮箱账号（用户登录账号）"
							show-count
						/>
					</a-form-item>
					<a-form-item name="phone" label="联系电话">
						<a-input v-model:value="form.data.phone" :maxlength="11" placeholder="请输入用户联系电话" show-count />
					</a-form-item>
					<a-form-item name="password" label="密码">
						<a-input v-model:value="form.data.password" :maxlength="20" placeholder="请输入用户密码" show-count />
					</a-form-item>
				</a-form>
			</div>
		</meo-modal>

		<meo-modal v-model:open="detail.open" title="用户详情" cancel="关闭" :confirm="false">
			<a-row :gutter="[10, 20]">
				<a-col :span="8" class="text-align-right">用户头像：</a-col>
				<a-col :span="16">
					<meo-image :src="detail.data.picture?.url" :width="70" :heitht="70" />
				</a-col>
				<a-col :span="8" class="text-align-right">用户昵称：</a-col>
				<a-col :span="16">{{ detail.data.nickname }}</a-col>
				<a-col :span="8" class="text-align-right">邮箱账号：</a-col>
				<a-col :span="16">{{ detail.data.email }}</a-col>
				<a-col :span="8" class="text-align-right">联系电话：</a-col>
				<a-col :span="16">{{ detail.data.phone ?? '-' }}</a-col>
				<a-col :span="8" class="text-align-right">所属组织：</a-col>
				<a-col :span="16">
					<a-select v-model:value="detail.select">
						<a-select-option v-for="(item, index) in detail.data.organize_info" :key="item.id" :value="index">
							{{ item.name }}
						</a-select-option>
					</a-select>
				</a-col>
				<a-col :span="8" class="text-align-right">用户角色：</a-col>
				<a-col :span="16">{{ detail.data.organize_info[detail.select].role_info.name }}</a-col>
				<a-col :span="8" class="text-align-right">账号状态：</a-col>
				<a-col :span="16" :class="['color-error', 'color-success'][detail.data.status]">
					{{ ['封禁', '正常'][detail.data.status] }}
				</a-col>
				<a-col :span="8" class="text-align-right">最后一次登录时间：</a-col>
				<a-col :span="16">{{ detail.data.last_login_at ?? '-' }}</a-col>
				<a-col :span="8" class="text-align-right">账号注册时间：</a-col>
				<a-col :span="16">{{ detail.data.created_at }}</a-col>
			</a-row>
		</meo-modal>
	</div>
</template>

<style lang="scss" scoped>
	/** */
</style>
