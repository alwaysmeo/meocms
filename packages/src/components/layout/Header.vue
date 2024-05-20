<script setup></script>

<template>
	<div class="header">
		<div class="logo">
			<img src="" alt="logo" />
		</div>
		<div class="tools">
			<div>
				<tiny-tooltip content="消息通知" placement="bottom">
					<tiny-button icon="TinyIconPublicNotice" type="text" size="large" />
				</tiny-tooltip>
				<tiny-tooltip content="设置" placement="bottom">
					<tiny-button icon="TinyIconSetting" type="text" size="large" />
				</tiny-tooltip>
			</div>
			<tiny-dropdown :show-icon="false" trigger="click">
				<tiny-user-head type="icon" min round></tiny-user-head>
				<template #dropdown>
					<tiny-dropdown-menu visible-arrow>
						<tiny-dropdown-item>
							<tiny-icon-user class="header-dropdown-icon" />
							<span>用户中心</span>
						</tiny-dropdown-item>
						<tiny-dropdown-item>
							<tiny-icon-writing class="header-dropdown-icon" />
							<span>用户设置</span>
						</tiny-dropdown-item>
						<tiny-dropdown-item>
							<tiny-icon-derive class="header-dropdown-icon" />
							<span>退出登录</span>
						</tiny-dropdown-item>
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
		}
	}

	.header-dropdown-icon {
		vertical-align: text-top;
		+ span {
			padding-left: 5px;
		}
	}
	.tiny-user-head {
		--ti-userhead-head-small-size: 34px;
		--ti-dropdown-icon-color: white;
		--ti-dropdown-icon-size: 14px;
	}
</style>
