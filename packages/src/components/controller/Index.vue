<script setup>
	import { isEqual } from 'radash'
	import Controller from '@components/controller'

	const emits = defineEmits(['query'])

	const props = defineProps({
		list: {
			type: Array,
			default: () => []
		}
	})

	const controllerRef = ref()
	function reset() {
		controllerRef.value.map((item) => item.reset())
	}
	function query() {
		const params = {}
		for (const item of props.list) {
			switch (item.component) {
				case 'SelectInput':
					params[item.key.split(',')[0]] = isEqual(item.checked, '@null') ? undefined : item.checked?.replace?.('@', '')
					params[item.key.split(',')[1]] = item.value
					break
				case 'DateRangePicker':
					params[item.key] = item.value?.join?.(',')
					break
				case 'Select':
					params[item.key] = isEqual(item.checked, '@null') ? undefined : item.checked?.replace?.('@', '')
					break
				default:
					break
			}
		}
		emits('query', params)
	}
</script>

<template>
	<div class="primary-container controller-container">
		<div class="controller-body">
			<component
				ref="controllerRef"
				v-for="item in props.list"
				:key="item.key"
				:is="Controller[item.component]"
				:name="item.name"
				:placeholder="item.placeholder"
				v-model:checked="item.checked"
				v-model:value="item.value"
				:options="item.options"
			/>
		</div>
		<div class="controller-footer">
			<a-space>
				<a-button @click="reset">重置</a-button>
				<a-button type="primary" @click="query">查询</a-button>
			</a-space>
		</div>
	</div>
</template>

<style scoped lang="scss">
	.controller-container {
		.controller-body {
			display: flex;
			flex-wrap: wrap;
			gap: 10px;
		}
		.controller-footer {
			display: flex;
			justify-content: flex-end;
			margin-top: 10px;
		}
	}
</style>
