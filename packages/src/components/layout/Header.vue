<script setup>
	import { message } from 'ant-design-vue'
	import { useAsyncState } from '@vueuse/core'
	import { isEqual } from 'radash'
	import { useUserInfoStore } from '@stores/userInfoStore'
	import { useOrganizesStore } from '@stores/organizesStore'
	import { useModalConfirm } from '@hooks'
	import localforage from '@utils/localforage'
	import accountApi from '@apis/account'
	import i18n from '@language'

	const { t } = i18n.global
	const router = useRouter()
	const userInfoStore = useUserInfoStore()
	const organizesStore = useOrganizesStore()

	const { state: userInfo } = useAsyncState(userInfoStore.get())
	const { state: organizes } = useAsyncState(organizesStore.get())

	async function logout() {
		useModalConfirm({
			title: '提示',
			content: `确认退出【${userInfo.value.nickname}】账号？`,
			confirm: async () => {
				const { code } = await accountApi.logout()
				if (isEqual(code, 200)) {
					message.success(t('meo.tip.success.submit_logout'))
					await userInfoStore.clear()
					localStorage.clear()
					localforage.clear()
					router.replace({ name: 'login' })
				}
			}
		})
	}

	function changeOrganizes(item) {
		organizesStore.change(item)
		router.go(0)
	}
</script>

<template>
	<div class="header">
		<a-popover>
			<a-button type="text" size="small">
				<ant-user-outlined />
				<span>{{ userInfo?.nickname ?? '-' }}</span>
			</a-button>
			<template #content>
				<div class="userinfo-container">
					<div class="popover-body">
						<div>
							<ant-history-outlined />
							<span>登录时间：{{ userInfo?.last_login_at ?? '-' }}</span>
						</div>
						<div>
							<ant-laptop-outlined />
							<span>IP地址：0.0.0.0</span>
						</div>
					</div>
					<div class="popover-footer">
						<a-button type="text">
							<ant-user-outlined />
							<span>个人中心</span>
						</a-button>
						<a-button type="text" @click="logout">
							<ant-login-outlined />
							<span>退出登录</span>
						</a-button>
					</div>
				</div>
			</template>
		</a-popover>
		<a-tooltip title="消息通知" placement="bottom">
			<a-button type="text" size="small">
				<ant-message-outlined />
			</a-button>
		</a-tooltip>
		<a-tooltip title="设置" placement="bottom">
			<a-button type="text" size="small">
				<ant-setting-outlined />
			</a-button>
		</a-tooltip>
		<a-popover placement="bottomLeft" overlayClassName="meo-popover-container" trigger="focus">
			<a-button type="text" size="small">
				<ant-bank-outlined />
				<span>{{ organizes?.checked?.name }}</span>
			</a-button>
			<template #content>
				<div class="site-container">
					<a-button
						type="text"
						v-for="item in organizes.list"
						:class="{ 'color-primary': isEqual(item.id, organizes?.checked?.id) }"
						:key="item.id"
						@click="changeOrganizes(item)"
					>
						{{ item.name }}
					</a-button>
				</div>
			</template>
		</a-popover>
	</div>
</template>

<style scoped lang="scss">
	.header {
		display: flex;
		align-items: center;
		gap: 10px;
		:deep(button) {
			color: #666;
		}
	}
	.userinfo-container {
		.popover-body {
			> div {
				padding: 4px 10px;
				display: flex;
				align-items: center;
				gap: 8px;
			}
		}
		.popover-footer {
			display: flex;
			align-items: center;
			justify-content: space-between;
		}
	}
	.site-container {
		display: grid;
		grid-template-columns: repeat(1, 1fr);
		max-height: 380px;
		overflow-y: auto;
		:deep(button) {
			text-align: left;
		}
	}
</style>
