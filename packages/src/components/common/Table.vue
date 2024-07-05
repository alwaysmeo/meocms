<!-- 表格组件 -->
<script setup>
	import { isEqual, first, omit } from 'radash'

	const emits = defineEmits(['paginate', 'action'])

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
		selector: {
			type: Boolean,
			default: false,
			message: '表头选择器'
		}
	})

	const pagination = reactive({
		current: 1,
		pageSize: 10
	})

	const action_first = computed(() => {
		return {
			key: first(Object.keys(props.action)),
			value: first(Object.values(props.action))
		}
	})
	const action_list = computed(() => {
		return omit(props.action, [action_first.value.key])
	})

	function change(paginate, filters, sorter, { action, currentDataSource }) {
		Object.assign(pagination, paginate)
		emits(
			action,
			{
				page: pagination.current,
				limit: pagination.pageSize,
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
</script>

<template>
	<a-table
		class="custom-table"
		@change="change"
		:pagination="{ ...pagination, total: props.total }"
		:scroll="{ x: 1200 }"
		v-bind="$attrs"
		:columns="props.columns"
	>
		<template #bodyCell="scoped">
			<template v-if="isEqual(scoped.column.dataIndex, 'index')">
				{{ (pagination.current - 1) * pagination.pageSize + (scoped.index + 1) }}
			</template>
			<slot name="bodyCell" v-bind="scoped"></slot>
			<template v-if="isEqual(scoped.column.dataIndex, 'action')">
				<a-space>
					<a-button
						class="color-primary"
						type="text"
						size="small"
						@click="handleAction(action_first.key, scoped.record)"
					>
						{{ action_first.value }}
					</a-button>
					<meo-dropdown :list="action_list" @menu="handleAction($event, scoped.record)" />
				</a-space>
			</template>
		</template>
		<template #emptyText>
			<div class="text-align-center">{{ $attrs.emptyTxt ?? '暂无数据' }}</div>
		</template>
	</a-table>
</template>

<style scoped lang="scss">
	/** table */
</style>
