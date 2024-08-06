<!-- 树形控件组件：tree component -->
<script setup>
	import { useVModel } from '@vueuse/core'
	import { useUnfoldTree } from '@hooks'
	import { isEqual, isEmpty, flat } from 'radash'

	const emits = defineEmits(['update:value'])

	const props = defineProps({
		treeData: {
			type: Array,
			default: () => []
		},
		value: {
			type: Array,
			default: () => []
		}
	})

	const state = reactive({
		value: useVModel(props, 'value', emits),
		checkedKeys: computed(() => {
			const checked = []
			const halfChecked = []
			const unfold = useUnfoldTree(props.treeData)
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

	function check(checkedKeys, e) {
		state.value = flat([checkedKeys, e.halfCheckedKeys])
	}
</script>

<template>
	<a-tree
		checkable
		:fieldNames="{ children: 'children', title: 'name', key: 'id' }"
		v-bind="$attrs"
		:checkedKeys="state.checkedKeys"
		:treeData="props.treeData"
		@check="check"
	/>
</template>

<style scoped lang="scss">
	/** */
</style>
