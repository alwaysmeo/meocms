<script setup>
	import { useVModel } from '@vueuse/core'

	const emits = defineEmits(['update:checked'])

	const props = defineProps({
		name: {
			type: String,
			default: ''
		},
		checked: {
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
			default: '请选择要搜索的内容'
		}
	})

	const state = reactive({
		checked: useVModel(props, 'checked', emits)
	})

	function reset() {
		state.checked = undefined
	}

	defineExpose({ reset })
</script>

<template>
	<div class="meo-select-container">
		<a-tooltip :title="props.name">
			<p class="text-overflow">
				{{ props.name }}
			</p>
		</a-tooltip>
		<a-select v-model:value="state.checked" :placeholder="props.placeholder">
			<template v-for="(item, key) in props.options" :key="key">
				<a-select-option :value="key">{{ item }}</a-select-option>
			</template>
		</a-select>
	</div>
</template>

<style scoped lang="scss">
	.meo-select-container {
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
		:deep(.ant-select-selector) {
			width: 100%;
			border-radius: 0 6px 6px 0;
			margin-left: -1px;
		}
	}
</style>
