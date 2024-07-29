<!-- 表格组件：table component -->
<script setup>
	import { useVModel } from '@vueuse/core'
	import { isEqual, first, omit } from 'radash'
	import { vDraggable } from 'vue-draggable-plus'

	const emits = defineEmits(['update:open', 'update:columns', 'update:page', 'update:limit', 'paginate'])

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
		pageSizeOptions: {
			type: Array,
			default: () => ['10', '20', '50', '100'],
			message: '指定每页可以显示多少条'
		},
		action: {
			type: Object,
			default: () => {},
			message: '操作列中的数据对象：key: { name, show, disabled, click }'
		}
	})

	const state = reactive({
		page: 1,
		limit: 10,
		action_first_key: computed(() => {
			return first(Object.keys(props.action))
		}),
		columns: useVModel(props, 'columns', emits)
	})

	const checkbox = ref(props.columns.filter((item) => item.show).map((item) => item.dataIndex))

	function handleChange(paginate, filters, sorter, { action }) {
		emits('update:page', paginate.current)
		emits('update:limit', paginate.pageSize)
		emits(action)
	}

	function selector(value) {
		for (const item of props.columns) {
			if (!isEqual(item.dataIndex, 'index')) {
				item.show = value.includes(item.dataIndex)
			}
		}
	}
</script>

<template>
	<div class="meo-table-container">
		<div class="meo-table-header">
			<a-tooltip title="刷新">
				<a-button type="text" size="small" @click="emits('paginate')">
					<ant-reload-outlined />
				</a-button>
			</a-tooltip>
			<a-popover placement="bottomRight" trigger="click">
				<a-tooltip title="列设置">
					<a-button type="text" size="small">
						<ant-setting-outlined />
					</a-button>
				</a-tooltip>
				<template #content>
					<a-checkbox-group
						v-draggable="[state.columns, { animation: 300, handle: '.handle' }]"
						class="checkbox-container"
						v-model:value="checkbox"
						@change="selector"
					>
						<div class="checkbox-item" v-for="item in state.columns" :key="item.dataIndex">
							<template v-if="!isEqual(item.dataIndex, 'index')">
								<ant-holder-outlined class="handle" />
								<a-checkbox :value="item.dataIndex" class="user-select-none">
									{{ item.title }}
								</a-checkbox>
							</template>
						</div>
					</a-checkbox-group>
				</template>
			</a-popover>
		</div>
		<a-table
			class="meo-table"
			rowKey="id"
			:pagination="{ current: props.page, pageSize: props.limit, total: props.total, pageSizeOptions: props.pageSizeOptions }"
			:scroll="{ x: 1200 }"
			@change="handleChange"
			v-bind="$attrs"
			:columns="state.columns.filter((item) => item.show)"
		>
			<template #bodyCell="scoped">
				<template v-if="isEqual(scoped.column.dataIndex, 'index')">
					{{ (props.page - 1) * props.limit + (scoped.index + 1) }}
				</template>
				<slot name="bodyCell" v-bind="scoped"></slot>
				<template v-if="isEqual(scoped.column.dataIndex, 'action')">
					<a-space>
						<a-button class="color-primary" type="text" size="small" @click="props.action[state.action_first_key].event(scoped.record)">
							{{ props.action[state.action_first_key].name }}
						</a-button>
						<a-popover placement="bottomRight" overlayClassName="meo-popover-container" trigger="focus">
							<a-button type="text" size="small">
								<span class="color-primary">更多</span>
								<ant-down-outlined class="color-primary" />
							</a-button>
							<template #content>
								<div class="action-container">
									<a-space direction="vertical" :size="0">
										<template v-for="(item, key) in omit(props.action, [state.action_first_key])" :key="key">
											<a-button
												type="text"
												v-if="item?.show?.(scoped.record) ?? true"
												:disabled="item?.disabled?.(scoped.record)"
												@click="item.event(scoped.record)"
											>
												{{ item.name }}
											</a-button>
										</template>
									</a-space>
								</div>
							</template>
						</a-popover>
					</a-space>
				</template>
			</template>
		</a-table>
	</div>
</template>

<style scoped lang="scss">
	.meo-table-container {
		position: relative;
		.meo-table-header {
			display: flex;
			align-items: center;
			justify-content: flex-end;
			gap: 4px;
			padding: 4px;
		}
	}
	.checkbox-container {
		display: grid;
		grid-template-columns: repeat(1, 1fr);
		gap: 10px;
		cursor: default;
		.checkbox-item {
			display: flex;
			align-items: center;
			gap: 6px;
			.handle {
				padding-left: 4px;
				cursor: move;
			}
		}
	}
</style>
