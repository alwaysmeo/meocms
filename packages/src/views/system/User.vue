<script setup>
	import { message } from 'ant-design-vue'
	import { isEmpty, isEqual, pick } from 'radash'
	import { useOrganizesStore } from '@stores/organizesStore'
	import rexExp from '@utils/rexExp'
	import usersApi from '@apis/users'
	import rolesApi from '@apis/roles'
	import i18n from '@language'

	defineOptions({ name: 'SystemUser' })

	const { t } = i18n.global
	const organizesStore = useOrganizesStore()

	const table = reactive({
		columns: [
			{ dataIndex: 'index', title: t('meo.pages.system.user.table.columns.index'), width: 120, align: 'center', show: true },
			{ dataIndex: 'ulid', title: t('meo.pages.system.user.table.columns.id'), width: 260 },
			{ dataIndex: 'picture', title: t('meo.pages.system.user.table.columns.picture'), width: 120, align: 'center', show: true },
			{ dataIndex: 'email', title: t('meo.pages.system.user.table.columns.email'), show: true },
			{ dataIndex: 'phone', title: t('meo.pages.system.user.table.columns.phone'), width: 150, align: 'center' },
			{ dataIndex: 'nickname', title: t('meo.pages.system.user.table.columns.nickname'), show: true },
			{ dataIndex: 'role', title: t('meo.pages.system.user.table.columns.role'), align: 'center', show: true },
			{ dataIndex: 'created_at', title: t('meo.pages.system.user.table.columns.created_at'), width: 170, align: 'center' },
			{ dataIndex: 'last_login_at', title: t('meo.pages.system.user.table.columns.last_login_at'), width: 170, align: 'center' },
			{ dataIndex: 'status', title: t('meo.pages.system.user.table.columns.status'), width: 120, align: 'center', show: true },
			{ dataIndex: 'action', title: t('meo.pages.system.user.table.columns.action'), width: 160, align: 'center', show: true }
		],
		data: [],
		open: false,
		loading: true,
		page: 1,
		limit: 10,
		total: 0,
		action: (key, record) => {
			return {
				edit: async () => {
					if (isEmpty(form.role_list)) {
						const { code, data } = await rolesApi.list({ organize_id: organizes.value.checked.id })
						if (isEqual(code, 200)) form.role_list = data.list
					}
					form.data = pick(record, ['ulid', 'nickname', 'email', 'phone'])
					form.data.role_id = record.role_info?.id
					form.open = true
				},
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

	const organizes = ref()
	onMounted(async () => {
		organizes.value = await organizesStore.get()
		await list()
	})

	async function list() {
		table.loading = true
		const { code, data } = await usersApi.list({
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

	async function upsert() {
		const { code } = await usersApi.upsert({
			organize_id: organizes.value.checked.id,
			...form.data
		})
		if (isEqual(code, 200)) {
			await list()
			form.open = false
			form.data = {}
		}
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
					<a-button type="primary" @click="table.action('edit', {})">新增用户</a-button>
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
					<template v-if="isEqual(column.dataIndex, 'picture')">
						<a-avatar :size="50" :src="record?.picture?.url">
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

		<meo-modal v-model:open="form.open" :title="form.data.ulid ? '修改用户' : '新增用户'" @confirm="form.submit">
			<div>
				<a-form ref="formRef" :model="form.data" :rules="form.rules" :label-col="{ span: 6 }" :wrapper-col="{ span: 16 }">
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
						<a-input v-model:value="form.data.email" :maxlength="60" placeholder="请输入邮箱账号（用户登录账号）" show-count />
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
	</div>
</template>

<style lang="scss" scoped>
	/** */
</style>
