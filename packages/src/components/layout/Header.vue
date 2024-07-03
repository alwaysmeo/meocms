<script setup></script>

<template>
	<div class="header">
		<a-popover>
			<a-button type="text" size="small">
				<template #icon>
					<ant-user-outlined />
				</template>
				<span>用户</span>
			</a-button>
			<template #content>
				<div class="userinfo-container">
					<div class="popover-body">
						<div>
							<ant-history-outlined />
							<span>登录时间：2024-06-04 19:01:12</span>
						</div>
						<div>
							<ant-laptop-outlined />
							<span>IP地址：114.114.114.114</span>
						</div>
					</div>
					<div class="popover-footer">
						<a-button type="text">
							<template #icon>
								<ant-user-outlined />
							</template>
							<span>个人中心</span>
						</a-button>
						<a-button type="text">
							<template #icon>
								<ant-login-outlined />
							</template>
							<span>退出登录</span>
						</a-button>
					</div>
				</div>
			</template>
		</a-popover>
		<a-tooltip title="消息通知" placement="bottom">
			<a-button type="text" size="small">
				<template #icon>
					<ant-message-outlined />
				</template>
			</a-button>
		</a-tooltip>
		<a-tooltip title="设置" placement="bottom">
			<a-button type="text" size="small">
				<template #icon>
					<ant-setting-outlined />
				</template>
			</a-button>
		</a-tooltip>
		<a-popover placement="bottomLeft" overlayClassName="site-popover">
			<a-button type="text" size="small">
				<template #icon>
					<ant-bank-outlined />
				</template>
				<span>站点名称</span>
			</a-button>
			<template #content>
				<div class="site-container">
					<a-button type="text">站点名称</a-button>
					<a-button type="text">站点名称</a-button>
					<a-button type="text">站点名称</a-button>
					<a-button type="text">站点名称</a-button>
					<a-button type="text">站点名称</a-button>
					<a-button type="text">站点名称</a-button>
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
