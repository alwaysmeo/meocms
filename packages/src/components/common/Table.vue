<!-- 表格组件 -->
<script setup>
	import { isEqual, first, omit } from 'radash'
	import { useVModel } from '@vueuse/core'

	const emits = defineEmits(['update:open', 'paginate', 'action'])

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
		action: {
			type: Object,
			default: () => new Object(),
			message: '操作列中的数据对象：{ key: value }'
		},
		open: {
			type: Boolean,
			default: false,
			message: '控制选择可见的表格列的模态框显示隐藏 v-model:open'
		}
	})

	const state = reactive({
		page: 1,
		limit: 10,
		open: useVModel(props, 'open', emits),
		action_first: computed(() => new Object({ key: first(Object.keys(props.action)), value: first(Object.values(props.action)) })),
		action_list: computed(() => omit(props.action, [state.action_first.value.key]))
	})

	function change(paginate, filters, sorter, { action, currentDataSource }) {
		state.page = paginate.current
		state.limit = paginate.pageSize
		emits(
			action,
			{
				page: state.page,
				limit: state.limit,
				total: props.total
			},
			filters,
			sorter,
			{
				action,
				currentDataSource
			}
		)
	}

	function handleAction(key, record) {
		emits('action', key, record)
	}

	function confirm() {
		console.log(props.columns)
	}
</script>

<template>
	<div class="meo-table-container">
		<a-table
			class="meo-table"
			@change="change"
			:pagination="{ current: state.page, pageSize: state.limit, total: props.total }"
			:scroll="{ x: 1200 }"
			v-bind="$attrs"
			:columns="props.columns"
		>
			<template #bodyCell="scoped">
				<template v-if="isEqual(scoped.column.dataIndex, 'index')">
					{{ (state.page - 1) * state.limit + (scoped.index + 1) }}
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
				<div class="text-align-center">{{ $attrs.emptyTxt ?? '暂无数据' }}</div>
			</template>
		</a-table>
		<a-modal v-model:open="state.open" title="选择可见的表格列" centered @ok="confirm" @cancel="state.open = false">
			<p>Some contents...</p>
			<p>Some contents...</p>
			<p>Some contents...</p>
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
</style>
