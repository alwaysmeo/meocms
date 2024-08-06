<script setup>
	import stores from '@stores'
	import Header from './Header.vue'
	import Sidebar from './Sidebar.vue'
	import AppMain from './AppMain.vue'
	import Footer from './Footer.vue'

	const sidebarStore = stores.useSidebarStore()

	const collapsed = computed(() => {
		return sidebarStore.getCollapsed()
	})
</script>

<template>
	<div id="layout" class="layout">
		<a-layout>
			<a-layout-sider :collapsed="collapsed" :width="240" :trigger="null" collapsible theme="light">
				<Sidebar />
			</a-layout-sider>
			<a-layout>
				<a-layout-header>
					<ant-menu-fold-outlined
						class="flip-button"
						:class="{ flip: collapsed }"
						@click="sidebarStore.changeCollapsed()"
					/>
					<Header />
				</a-layout-header>
				<a-layout-content>
					<AppMain>
						<template #footer>
							<Footer />
						</template>
					</AppMain>
				</a-layout-content>
			</a-layout>
		</a-layout>
	</div>
</template>

<style scoped lang="scss">
	.layout {
		.ant-layout-header {
			padding: 0 20px;
			display: flex;
			align-items: center;
			justify-content: space-between;
			background-color: white;
			border-bottom: 1px solid #eee;
			.flip-button {
				font-size: 20px;
				transition: transform 0.6s;
				&.flip {
					transform: rotateY(180deg);
				}
			}
		}
	}
</style>
