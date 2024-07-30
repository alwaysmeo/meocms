<script setup>
	import { useVModel } from '@vueuse/core'

	const emits = defineEmits(['update:value'])

	const props = defineProps({
		checked: {
			type: String,
			default: null
		},
		value: {
			type: String,
			default: ''
		},
		options: {
			type: Object,
			default: () => {}
		},
		placeholder: {
			type: String,
			default: '请输入要搜索的内容'
		}
	})

	const state = reactive({
		checked: useVModel(props, 'checked', emits),
		value: useVModel(props, 'value', emits)
	})

	function reset() {
		state.value = ''
	}

	defineExpose({ reset })
</script>

<template>
	<div class="meo-select-input-container">
		<a-select ref="select" v-model:value="state.checked">
			<a-select-option v-for="(item, key) in props.options" :key="key" :value="key">{{ item }}</a-select-option>
		</a-select>
		<a-input v-model:value.trim="state.value" :placeholder="props.placeholder" />
	</div>
</template>

<style scoped lang="scss">
	.meo-select-input-container {
		max-width: 300px;
		width: 100%;
		display: grid;
		grid-template-columns: 120px 1fr;
		:deep(.ant-select-selector) {
			border-radius: 6px 0 0 6px;
		}
		:deep(.ant-input) {
			border-radius: 0 6px 6px 0;
			margin-left: -1px;
		}
	}
</style>
