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
		{{ count }}
		<tiny-icon-share></tiny-icon-share>
		<tiny-button type="primary" native-type="submit" @click="count++">主要按钮</tiny-button>
	</div>
</template>

<style lang="scss" scoped>
	/** */
</style>
