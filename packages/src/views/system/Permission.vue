<script setup>
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
		<tiny-button type="primary" native-type="submit" @click="count++">{{ count }} 权限管理</tiny-button>
	</div>
</template>

<style lang="scss" scoped>
	/** */
</style>
