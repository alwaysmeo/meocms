<script setup>
	defineOptions({ name: 'Home' })
	const count = ref(0)
</script>

<template>
	<div class="always-container">
		<a-button type="primary" native-type="submit" @click="count++">{{ count }} 主要按钮</a-button>
	</div>
</template>

<style lang="scss" scoped>
	/** */
</style>
