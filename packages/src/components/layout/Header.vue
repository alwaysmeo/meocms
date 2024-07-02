<script setup></script>

<template>
	<div class="header">
		<a-popover>
			<div class="user">
				<ant-user-outlined />
				<span>用户</span>
			</div>
			<template #content>
				<div class="user-popover-container">
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
						<tiny-button type="text" icon="TinyIconUser">个人中心</tiny-button>
						<tiny-button type="text" icon="TinyIconDerive">退出登录</tiny-button>
					</div>
				</div>
			</template>
		</a-popover>
		<div class="tools">
			<a-tooltip content="消息通知" placement="bottom">
				<a-button type="text" shape="circle" size="small">
					<template #icon>
						<ant-message-outlined />
					</template>
				</a-button>
			</a-tooltip>
			<a-tooltip content="设置" placement="bottom">
				<a-button type="text" shape="circle" size="small">
					<template #icon>
						<ant-message-outlined />
					</template>
				</a-button>
			</a-tooltip>
		</div>
		<a-dropdown class="site-dropdown" :show-icon="false" trigger="click" inherit-width>
			<div class="now-site">
				<ant-home-outlined />
				<span>站点名称</span>
			</div>
			<template #overlay>
				<a-menu>
					<a-menu-item>站点1</a-menu-item>
					<a-menu-item>站点1</a-menu-item>
					<a-menu-item>站点1</a-menu-item>
				</a-menu>
			</template>
		</a-dropdown>
	</div>
</template>

<style scoped lang="scss">
	.header {
		display: flex;
		align-items: center;
		gap: 20px;
		.user {
			display: flex;
			align-items: center;
			gap: 10px;
			cursor: pointer;
			span {
				font-size: 14px;
				color: #666;
			}
		}
		.tools {
			display: flex;
			align-items: center;
			gap: 10px;
			:deep(button) {
				color: #666;
			}
		}
		.now-site {
			display: flex;
			align-items: center;
			gap: 4px;
			color: #666;
			cursor: pointer;
		}
	}
	.user-popover-container {
		.popover-body {
			margin-bottom: 12px;
			> div {
				display: flex;
				align-items: center;
				gap: 5px;
				svg {
					fill: #666;
				}
				&:not(:last-child) {
					margin-bottom: 6px;
				}
			}
		}
		.popover-footer {
			display: flex;
			align-items: center;
			justify-content: space-between;
			gap: 10px;
		}
	}
</style>
