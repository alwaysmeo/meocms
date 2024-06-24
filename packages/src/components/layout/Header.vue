<script setup></script>

<template>
	<div class="header">
		<div class="logo">
			<img src="" alt="logo" />
		</div>
		<div class="tools">
			<tiny-popover placement="bottom-end" trigger="hover">
				<template #reference>
					<div class="user">
						<tiny-user-head type="icon" min round />
						<span>用户</span>
					</div>
				</template>
				<div class="user-popover-container">
					<div class="popover-body">
						<div>
							<tiny-icon-calendar />
							<span>登录时间：2024-06-04 19:01:12</span>
						</div>
						<div>
							<tiny-icon-mark-on />
							<span>IP地址：114.114.114.114</span>
						</div>
					</div>
					<div class="popover-footer">
						<tiny-button type="text" icon="TinyIconUser">个人中心</tiny-button>
						<tiny-button type="text" icon="TinyIconDerive">退出登录</tiny-button>
					</div>
				</div>
			</tiny-popover>
			<div>
				<tiny-tooltip content="消息通知" placement="bottom">
					<tiny-button icon="TinyIconPublicNotice" type="text" size="large" />
				</tiny-tooltip>
				<tiny-tooltip content="设置" placement="bottom">
					<tiny-button icon="TinyIconSetting" type="text" size="large" />
				</tiny-tooltip>
			</div>
			<tiny-dropdown class="site-dropdown" :show-icon="false" trigger="click" inherit-width>
				<div class="now-site">
					<tiny-icon-public-home />
					<span>站点名称</span>
				</div>
				<template #dropdown>
					<tiny-dropdown-menu visible-arrow>
						<tiny-dropdown-item tip="站点1" tip-position="left">站点1</tiny-dropdown-item>
						<tiny-dropdown-item tip="站点2" tip-position="left">站点2</tiny-dropdown-item>
						<tiny-dropdown-item tip="站点3" tip-position="left">站点3</tiny-dropdown-item>
					</tiny-dropdown-menu>
				</template>
			</tiny-dropdown>
		</div>
	</div>
</template>

<style scoped lang="scss">
	.header {
		padding: 0 20px;
		height: 60px;
		border-bottom: 1px solid var(--ti-common-color-line-dividing);
		display: flex;
		align-items: center;
		justify-content: space-between;
		.tools {
			display: flex;
			align-items: center;
			justify-content: flex-end;
			gap: 20px;
			.user {
				display: flex;
				align-items: center;
				gap: 10px;
				.tiny-user-head {
					--ti-userhead-head-small-size: 30px;
					--ti-userhead-head-icon-small-size: 12px;
				}
				span {
					font-size: 14px;
					color: #666;
				}
			}
			.site-dropdown {
				.now-site {
					display: flex;
					align-items: center;
					gap: 5px;
					font-size: 14px;
					--ti-dropdown-icon-size: 14px;
				}
			}
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
