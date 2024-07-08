<script setup>
	defineOptions({ name: 'Home' })
	const count = ref(false)
</script>

<template>
	<div class="always-container">
		<a-button type="primary" native-type="submit" @click="count = true">{{ count }} 主要按钮</a-button>
		<meo-modal v-model:open="count" title="用户详情" cancel="关闭" :confirm="false">
			<div>123</div>
		</meo-modal>
	</div>
</template>

<style lang="scss" scoped>
	/** */
</style>
