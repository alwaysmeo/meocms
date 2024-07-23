<script setup>
	import { useVModel } from '@vueuse/core'
	const emits = defineEmits(['update:open', 'cancel', 'confirm'])

	const props = defineProps({
		open: {
			type: Boolean,
			default: false
		},
		cancel: {
			type: [Boolean, String],
			default: '取消'
		},
		confirm: {
			type: [Boolean, String],
			default: '确定'
		},
		size: {
			type: String,
			default: 'large',
			validator: (value) => ['small', 'middle', 'large'].includes(value)
		}
	})

	const open = useVModel(props, 'open', emits)
	const loading = ref(false)

	async function onCancel() {
		await emits('cancel')
		open.value = false
	}

	function onConfirm() {
		loading.value = true
		emits('confirm')
	}

	watch(
		() => open.value,
		(value) => {
			if (!value) loading.value = false
		}
	)
</script>

<template>
	<a-modal
		class="meo-modal-container"
		centered
		:width="{ small: '320px', middle: '480px', large: '640px' }[props.size]"
		v-bind="$attrs"
		v-model:open="open"
		@cancel="onCancel"
	>
		<div class="meo-modal-body">
			<slot></slot>
		</div>
		<template #footer>
			<div class="meo-modal-footer">
				<slot name="footer" v-if="$slots.footer"></slot>
				<template v-else>
					<a-button v-if="props.cancel" @click="onCancel">{{ props.cancel }}</a-button>
					<a-button type="primary" v-if="props.confirm" @click="onConfirm" :loading="loading">{{ props.confirm }}</a-button>
				</template>
			</div>
		</template>
	</a-modal>
</template>

<style scoped lang="scss">
	.meo-modal-container {
		:deep(.ant-modal-header) {
			margin-bottom: 20px;
		}
		.meo-modal-body {
			overflow-y: auto;
			overflow-x: hidden;
			max-height: 500px;
		}
	}
</style>
