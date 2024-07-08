<!-- 表格组件：table component -->
<script setup>
	import { isEqual, first, omit } from 'radash'
	import { useVModel } from '@vueuse/core'

	const emits = defineEmits(['update:open', 'update:columns', 'update:page', 'update:limit', 'paginate', 'action'])

	const props = defineProps({
		columns: {
			type: Array,
			default: () => new Array(),
			message: '表格列的配置'
		},
		total: {
			type: Number,
			default: 0,
			validator: (val) => val >= 0,
			message: '数据总数'
		},
		page: {
			type: Number,
			default: 1,
			validator: (val) => val >= 0,
			message: '页码'
		},
		limit: {
			type: Number,
			default: 10,
			validator: (val) => val >= 0,
			message: '数量限制'
		},
		action: {
			type: Object,
			default: () => new Object(),
			message: '操作列中的数据对象：{ key: value }'
		},
		open: {
			type: Boolean,
			default: false,
			message: '控制选择可见的表格列的模态框显示隐藏 v-model:open，（需设置 v-model:columns）'
		}
	})

	const state = reactive({
		page: 1,
		limit: 10,
		open: useVModel(props, 'open', emits),
		action_first: computed(() => new Object({ key: first(Object.keys(props.action)), value: first(Object.values(props.action)) })),
		action_list: computed(() => omit(props.action, [state.action_first.key])),
		columns: computed(() => props.columns.filter((item) => item.show))
	})

	const checkbox = ref(props.columns.filter((item) => item.show).map((item) => item.dataIndex))
	const checkbox_list = useVModel(props, 'columns', emits)

	function handleChange(paginate, filters, sorter, { action, currentDataSource }) {
		emits('update:page', paginate.current)
		emits('update:limit', paginate.pageSize)
		emits(action, { page: paginate.current, limit: paginate.pageSize, total: props.total }, filters, sorter, { action, currentDataSource })
	}

	function handleAction(key, record) {
		emits('action', key, record)
	}

	function handleConfirm() {
		for (const item of checkbox_list.value) item.show = checkbox.value.includes(item.dataIndex)
		state.open = false
	}
</script>

<template>
	<div class="meo-table-container">
		<a-table
			class="meo-table"
			:pagination="{ current: props.page, pageSize: props.limit, total: props.total }"
			:scroll="{ x: 1200 }"
			@change="handleChange"
			v-bind="$attrs"
			:columns="state.columns"
		>
			<template #bodyCell="scoped">
				<template v-if="isEqual(scoped.column.dataIndex, 'index')">
					{{ (props.page - 1) * props.limit + (scoped.index + 1) }}
				</template>
				<slot name="bodyCell" v-bind="scoped"></slot>
				<template v-if="isEqual(scoped.column.dataIndex, 'action')">
					<a-space>
						<a-button class="color-primary" type="text" size="small" @click="handleAction(state.action_first.key, scoped.record)">
							{{ state.action_first.value }}
						</a-button>
						<meo-dropdown :list="state.action_list" @menu="handleAction($event, scoped.record)" />
					</a-space>
				</template>
			</template>
			<template #emptyText>
				<div class="text-align-center">{{ $attrs.emptyTxt ?? $t('meo.components.common.table.not_data') }}</div>
			</template>
		</a-table>
		<a-modal
			:title="$t('meo.components.common.table.select_table_header')"
			v-model:open="state.open"
			centered
			class="modal-container"
			@ok="handleConfirm"
			@cancel="state.open = false"
		>
			<a-checkbox-group class="checkbox-container" v-model:value="checkbox">
				<a-checkbox v-for="item in checkbox_list" :key="item.dataIndex" :value="item.dataIndex" :disabled="['index', 'action'].includes(item.dataIndex)">
					<a-tooltip :title="item.title">
						{{ item.title }}
					</a-tooltip>
				</a-checkbox>
			</a-checkbox-group>
		</a-modal>
	</div>
</template>

<style scoped lang="scss">
	.meo-table-container {
		position: relative;
		.selector {
			position: absolute;
			top: 0;
			left: 0;
			z-index: 1;
			font-size: 20px;
		}
	}
	.modal-container {
		.checkbox-container {
			padding: 10px 30px 0 30px;
			width: 100%;
			display: grid;
			grid-template-columns: repeat(3, 1fr);
			gap: 10px;
			:deep(label) {
				overflow: hidden;
				> span:last-child {
					display: block;
					overflow: hidden;
					text-overflow: ellipsis;
					white-space: nowrap;
				}
			}
		}
	}
</style>
