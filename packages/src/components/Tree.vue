<script setup>
	import { useVModel } from '@vueuse/core'
	import { useUnfoldTree } from '@hooks'
	import { isEqual, isEmpty } from 'radash'

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
		checkedKeys: {
			checked: [],
			halfChecked: []
		}
	})

	watch(
		() => props.value,
		(value) => {
			const checked = []
			const halfChecked = []
			const unfold = useUnfoldTree(props.treeData)
			for (const item of unfold) {
				if (value.includes(item.id)) {
					const childs = unfold.filter((v) => isEqual(v.parent_id, item.id) && value.includes(v.id))
					if (isEmpty(item.children) || isEqual(item.children.length, childs.length)) {
						checked.push(item.id)
					} else {
						halfChecked.push(item.id)
					}
				}
			}
			state.checkedKeys = { checked, halfChecked }
		},
		{
			immediate: true
		}
	)

	function check(checkedKeys) {
		console.log(checkedKeys)
		state.value = checkedKeys
	}
</script>

<template>
	<a-tree
		checkable
		:fieldNames="{ children: 'children', title: 'name', key: 'id' }"
		v-bind="$attrs"
		v-model:checkedKeys="state.checkedKeys"
		:treeData="props.treeData"
		@check="check"
	/>
</template>

<style scoped lang="scss">
	/** */
</style>
