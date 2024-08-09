<script setup>
	import { Tree } from '@components'
	import { isEmpty, isEqual } from 'radash'
	import stores from '@stores'
	import columnsApi from '@apis/columns'

	defineOptions({ name: 'ColumnsManage' })
	const organizesStore = stores.useOrganizesStore()

	const state = reactive({
		loading: true,
		organizes: null,
		list: []
	})

	onMounted(async () => {
		state.organizes = await organizesStore.get()
		await list()
	})

	async function list() {
		state.loading = true
		const { code, data } = await columnsApi.list({ organize_id: state.organizes.checked.id })
		if (isEqual(code, 200)) {
			state.list = data
		}
		state.loading = false
	}
</script>

<template>
	<div>
		<div class="primary-container">
			<div class="primary-header">
				<div>
					<div class="title">栏目管理</div>
					<div class="desc">网站栏目管理</div>
				</div>
			</div>
			<div class="primary-body">
				<div class="column-container">
					<a-empty v-if="!state.loading && isEmpty(state.list)" />
					<tree v-else v-model:tree-data="state.list" draggable block-node>
						<template #switcherIcon="{ switcherCls }">
							<ant-down-outlined :class="switcherCls" />
						</template>
					</tree>
					<meo-loading :spinning="state.loading" />
				</div>
			</div>
		</div>
	</div>
</template>

<style scoped lang="scss">
	.column-container {
		position: relative;
		padding: 10px;
		max-width: 300px;
		min-height: 600px;
		border: 1px solid #d9d9d9;
		border-radius: 6px;
	}
</style>
