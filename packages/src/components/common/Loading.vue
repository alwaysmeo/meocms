<script setup>
	const props = defineProps({
		top: {
			type: String,
			default: '30%'
		},
		tip: {
			type: String,
			default: '加载中...'
		}
	})
</script>

<template>
	<a-spin class="spin-container" v-bind="$attrs" :style="{ top: props.top }" :tip="props.tip" :delay="100" />
</template>

<style scoped lang="scss">
	.spin-container {
		position: absolute;
		left: 50%;
		transform: translateX(-50%);
	}
</style>
