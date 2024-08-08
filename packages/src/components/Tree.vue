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
		}
	})

	const state = reactive({
		treeData: useVModel(props, 'treeData', emits),
		value: useVModel(props, 'value', emits),
		checkedKeys: computed(() => {
			const checked = []
			const halfChecked = []
			const unfold = hooks.useUnfoldTree(props.treeData)
			for (const item of unfold) {
				if (props.value.includes(item.id)) {
					const childs = unfold.filter((v) => isEqual(v.parent_id, item.id) && props.value.includes(v.id))
					if (isEmpty(item.children) || isEqual(item.children.length, childs.length)) {
						checked.push(item.id)
					} else {
						halfChecked.push(item.id)
					}
				}
			}
			return { checked, halfChecked }
		})
	})

	function drop(info) {
		const dropKey = info.node.key
		const dragKey = info.dragNode.key
		const dropPos = info.node.pos.split('-')
		const dropPosition = info.dropPosition - Number(dropPos[dropPos.length - 1])
		const loop = (data, key, callback) => {
			data.forEach((item, index) => {
				if (item.key === key) {
					return callback(item, index, data)
				}
				if (item.children) {
					return loop(item.children, key, callback)
				}
			})
		}
		const data = [...state.treeData]

		// Find dragObject
		let dragObj
		loop(data, dragKey, (item, index, arr) => {
			arr.splice(index, 1)
			dragObj = item
		})
		if (!info.dropToGap) {
			// Drop on the content
			loop(data, dropKey, (item) => {
				item.children = item.children || []
				/// where to insert 示例添加到头部，可以是随意位置
				item.children.unshift(dragObj)
			})
		} else if (
			(info.node.children || []).length > 0 &&
			// Has children
			info.node.expanded &&
			// Is expanded
			dropPosition === 1 // On the bottom gap
		) {
			loop(data, dropKey, (item) => {
				item.children = item.children || []
				// where to insert 示例添加到头部，可以是随意位置
				item.children.unshift(dragObj)
			})
		} else {
			let ar = []
			let i = 0
			loop(data, dropKey, (_item, index, arr) => {
				ar = arr
				i = index
			})
			if (dropPosition === -1) {
				ar.splice(i, 0, dragObj)
			} else {
				ar.splice(i + 1, 0, dragObj)
			}
		}
		state.treeData = data
	}

	function check(checkedKeys, e) {
		state.value = flat([checkedKeys, e.halfCheckedKeys])
	}
</script>

<template>
	<a-tree
		:fieldNames="{ children: 'children', title: 'name', key: 'id' }"
		v-bind="$attrs"
		:checkedKeys="state.checkedKeys"
		:treeData="state.treeData"
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
