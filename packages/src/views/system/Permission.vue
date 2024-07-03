<script setup>
	defineOptions({ name: 'SystemPermission' })
	const count = ref(0)

	onActivated(() => {
		console.log('count.value', count.value)
	})
	onDeactivated(() => {
		console.log('count.value', count.value)
	})
</script>

<template>
	<div class="always-container">
		<a-button type="primary" @click="count++">{{ count }} 权限管理</a-button>
	</div>
</template>

<style lang="scss" scoped>
	/** */
</style>
