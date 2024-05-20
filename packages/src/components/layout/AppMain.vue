<script setup>
	import Footer from './Footer.vue'
</script>

<template>
	<div class="main-app">
		<div class="main-app-container">1</div>
		<Footer />
	</div>
</template>

<style scoped lang="scss">
	.main-app {
		display: grid;
		grid-template-columns: repeat(1, 1fr);
		gap: 20px;
		&-container {
			height: calc(100vh - 60px - 60px);
			overflow-y: auto;
			padding: 20px;
		}
	}
</style>
