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

	function onCancel() {
		emits('cancel')
		open.value = false
	}
</script>

<template>
	<a-modal centered :width="{ small: '320px', middle: '480px', large: '640px' }[props.size]" v-bind="$attrs" v-model:open="open" @cancel="onCancel">
		<slot></slot>
		<template #footer>
			<slot name="footer" v-if="$slots.footer"></slot>
			<template v-else>
				<a-button v-if="props.cancel" @click="onCancel">{{ props.cancel }}</a-button>
				<a-button type="primary" v-if="props.confirm" @click="emits('confirm')">{{ props.confirm }}</a-button>
			</template>
		</template>
	</a-modal>
</template>

<style scoped lang="scss">
	/** */
</style>
