<script setup>
	import Header from './Header.vue'
	import Sidebar from './Sidebar.vue'
	import AppMain from './AppMain.vue'
</script>

<template>
	<div id="layout" class="layout">
		<Header />
		<div class="main">
			<Sidebar />
			<div>
				<AppMain />
			</div>
		</div>
	</div>
</template>

<style scoped lang="scss">
	.layout {
		.main {
			display: grid;
			grid-template-columns: auto 1fr;
		}
	}
</style>
