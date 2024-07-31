<script setup>
	import { useVModel } from '@vueuse/core'

	const emits = defineEmits(['update:value'])

	const props = defineProps({
		name: {
			type: String,
			default: ''
		},
		value: {
			type: Array,
			default: undefined
		},
		placeholder: {
			type: Array,
			default: () => ['开始时间', '结束时间']
		}
	})

	const state = reactive({
		value: useVModel(props, 'value', emits)
	})

	function reset() {
		state.value = undefined
	}

	defineExpose({ reset })
</script>

<template>
	<div class="meo-range-picker-container">
		<a-tooltip :title="props.name">
			<p class="text-overflow">
				{{ props.name }}
			</p>
		</a-tooltip>
		<a-range-picker v-model:value="state.value" :placeholder="props.placeholder" valueFormat="YYYY-MM-DD" />
	</div>
</template>

<style scoped lang="scss">
	.meo-range-picker-container {
		max-width: 350px;
		width: 100%;
		display: grid;
		grid-template-columns: 100px 1fr;
		> p {
			padding: 0 10px;
			border: 1px solid #d9d9d9;
			border-radius: 6px 0 0 6px;
			line-height: 30px;
		}
		:deep(.ant-picker) {
			border-radius: 0 6px 6px 0;
			margin-left: -1px;
		}
	}
</style>
