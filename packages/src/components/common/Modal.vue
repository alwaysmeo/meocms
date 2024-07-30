<script setup>
	import { useVModel } from '@vueuse/core'
	const emits = defineEmits(['update:open'])

	const props = defineProps({
		open: {
			type: Boolean,
			default: false
		},
		confirm: {
			type: [Boolean, String],
			default: '确定'
		},
		cancel: {
			type: [Boolean, String],
			default: '取消'
		},
		onConfirm: {
			type: Function,
			default: async () => {}
		},
		onCancel: {
			type: Function,
			default: async () => {}
		},
		size: {
			type: String,
			default: 'large',
			validator: (value) => ['small', 'middle', 'large'].includes(value)
		}
	})

	const open = useVModel(props, 'open', emits)
	const loading = ref(false)

	async function clickConfirm() {
		loading.value = true
		await props.onConfirm()
		loading.value = false
	}
	async function clickCancel() {
		await props.onCancel()
		open.value = false
	}
</script>

<template>
	<a-modal
		:class="{
			'meo-modal-button-confirm': !!props.confirm,
			'meo-modal-button-cancel': !!props.cancel
		}"
		centered
		:width="{ small: '320px', middle: '480px', large: '640px' }[props.size]"
		v-bind="$attrs"
		v-model:open="open"
		:okText="props.confirm"
		:cancelText="props.cancel"
		:confirmLoading="loading"
		@ok="clickConfirm"
		@cancel="clickCancel"
	>
		<div class="meo-modal-body">
			<slot></slot>
		</div>
		<template #footer v-if="$slots.footer">
			<slot name="footer"></slot>
		</template>
	</a-modal>
</template>

<style scoped lang="scss">
	/** */
</style>
