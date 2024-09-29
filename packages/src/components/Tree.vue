<!-- 树形控件组件：tree component -->
<script setup>
	import { useVModel } from '@vueuse/core'
	import { isEqual, isEmpty, flat } from 'radash'
	import hooks from '@hooks'

	const emits = defineEmits(['update:treeData', 'update:value'])

	const props = defineProps({
		treeData: {
			type: Array,
			default: () => [],
			message: '数据列表（可选：v-model）'
		},
		value: {
			type: Array,
			default: () => [],
			message: '选中复选框的树节点（v-model）'
		},
		checkable: {
			type: Boolean,
			default: false,
			message: '节点前添加 Checkbox 复选框'
		}
	})

	const state = reactive({
		treeData: useVModel(props, 'treeData', emits),
		value: useVModel(props, 'value', emits),
		checkedKeys: computed(() => {
			const checked = []
			const halfChecked = []
			if (props.checkable) {
				const unfold = hooks.useUnfoldTree(props.treeData)
				console.log(unfold)
				for (const item of unfold) {
					if (props.value.includes(item.id)) {
						const child_selected = unfold.filter((v) => isEqual(v.parent_id, item.id) && props.value.includes(v.id))
						const child = unfold.filter((v) => isEqual(v.parent_id, item.id))
						if (isEqual(child_selected.length, child.length)) {
							checked.push(item.id)
						} else {
							halfChecked.push(item.id)
						}
					}
				}
			}
			console.log({ checked, halfChecked })
			return { checked, halfChecked }
		})
	})

	function drop(info) {
		console.log(info)
		// hooks.useBuildTree()
		const arr = hooks.useUnfoldTree(state.treeData)
		console.log(arr)
	}

	function check(checkedKeys, e) {
		// console.log(checkedKeys, e.halfCheckedKeys)
		// state.value = flat([checkedKeys, e.halfCheckedKeys])
	}
</script>

<template>
	<a-tree
		:fieldNames="{ children: 'children', title: 'name', key: 'id' }"
		v-bind="$attrs"
		v-model:checkedKeys="state.value"
		:treeData="state.treeData"
		:checkable="props.checkable"
		@check="check"
		@drop="drop"
	>
		<template v-if="$slots.switcherIcon" #switcherIcon="scoped">
			<slot name="switcherIcon" v-bind="scoped"></slot>
		</template>
	</a-tree>
</template>

<style scoped lang="scss">
	/** */
</style>
