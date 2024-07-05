<!-- 下拉菜单组件：dropdown menu component -->
<script setup>
	import { isEmpty } from 'radash'

	const emits = defineEmits(['menu'])

	const props = defineProps({
		desc: {
			type: String,
			default: '更多',
			message: '表格列的配置'
		},
		list: {
			type: Object,
			default: () => ({}),
			message: '列表的数据：{ key: value }'
		}
	})
</script>

<template>
	<a-dropdown v-bind="$attrs" trigger="click" placement="bottom" :getPopupContainer="(e) => e.parentNode.parentNode.parentNode">
		<slot name="overlay" v-if="$slots.default"></slot>
		<a-button v-else class="base-button" type="text" size="small">
			<span>{{ props.desc }}</span>
			<ant-down-outlined />
		</a-button>
		<slot name="overlay" v-if="$slots.overlay"></slot>
		<template #overlay>
			<a-menu v-if="!isEmpty(props.list)" @click="emits('menu', $event.key)">
				<a-menu-item v-for="(item, key) in props.list" :key="key">{{ item }}</a-menu-item>
			</a-menu>
		</template>
	</a-dropdown>
</template>

<style scoped lang="scss">
	.base-button {
		color: $color-primary;
		:deep(.anticon) {
			margin-inline-start: 2px;
		}
	}
</style>
