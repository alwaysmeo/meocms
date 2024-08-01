<script setup>
	import { useVModel } from '@vueuse/core'

	const emits = defineEmits(['update:checked', 'update:value'])

	const props = defineProps({
		checked: {
			type: String,
			default: undefined
		},
		value: {
			type: String,
			default: undefined
		},
		options: {
			type: Object,
			default: () => {},
			message: '请在 key 首位添加 @ 符号，例如 { @1:value, @2:value, @key:value }'
		},
		placeholder: {
			type: String,
			default: '请输入要搜索的内容'
		},
		placeholder_type: {
			type: String,
			default: '请选择'
		}
	})

	const state = reactive({
		checked: useVModel(props, 'checked', emits),
		value: useVModel(props, 'value', emits)
	})

	function reset() {
		state.checked = undefined
		state.value = undefined
	}

	defineExpose({ reset })
</script>

<template>
	<div class="meo-select-input-container">
		<a-select v-model:value="state.checked" :placeholder="props.placeholder_type">
			<a-select-option v-for="(item, key) in props.options" :key="key" :value="key">{{ item }}</a-select-option>
		</a-select>
		<a-input v-model:value.trim="state.value" :placeholder="props.placeholder" />
	</div>
</template>

<style scoped lang="scss">
	.meo-select-input-container {
		max-width: 350px;
		width: 100%;
		display: grid;
		grid-template-columns: 100px 1fr;
		:deep(.ant-select-selector) {
			padding: 0 10px;
			border-radius: 6px 0 0 6px;
		}
		:deep(.ant-input) {
			border-radius: 0 6px 6px 0;
			margin-left: -1px;
		}
	}
</style>
