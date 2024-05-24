<script setup>
	import { pascal } from 'radash'

	const route = useRoute()
	const router = useRouter()

	const tabs_active = ref('')
	const tabs_list = reactive([])

	watch(
		route,
		(to, from) => {
			const names = to.matched
				.filter((item) => item.name)
				.map((item) => item.name)
				.slice(0, 2)
			const name = names[names.length - 1]
			tabs_active.value = name
			console.log(route)
			if (!tabs_list.some((item) => item.name === name)) {
				tabs_list.push({ name, title: route.meta.title })
			}
		},
		{
			immediate: true
		}
	)

	const cacheAllow = computed(() => {
		console.log(tabs_list.map((item) => pascal(item.name)))
		return tabs_list.map((item) => pascal(item.name))
	})

	const component = ref(null)

	function setComp(com) {
		console.log(com)
		component.value = com
	}

	function closeTabs(name) {
		tabs_list.splice(
			tabs_list.findIndex((item) => item.name === name),
			1
		)
	}
</script>

<template>
	<div class="main-app">
		<div class="main-tabs">
			<tiny-tabs
				v-model="tabs_active"
				:with-close="true"
				tab-style="card"
				@click="router.push({ name: $event.name })"
				@close="closeTabs"
			>
				<tiny-tab-item v-for="item in tabs_list" :key="item.name" :title="item.title" :name="item.name" />
			</tiny-tabs>
		</div>
		<div class="main-breadcrumb">
			<tiny-breadcrumb>
				<tiny-breadcrumb-item
					v-for="(item, index) in $route.matched.filter((item) => item.name)"
					:key="item.name"
					:to="index ? { name: item.name } : {}"
					:label="item.meta.title"
					:class="{ disabled: !index && item.children.length }"
				/>
			</tiny-breadcrumb>
		</div>
		<router-view v-slot="{ Component: comp }">
			<transition appear name="fade-transform" mode="out-in">
				<keep-alive :include="['Home', 'SystemPermission', 'System', 'RouterView']">
					<component :is="(setComp(comp), component)" />
				</keep-alive>
			</transition>
		</router-view>
	</div>
</template>

<style scoped lang="scss">
	.main-app {
		height: calc(100vh - 60px - 60px);
		overflow-y: auto;
		padding: 20px;
		.main-breadcrumb {
			margin-bottom: 10px;
			.disabled :deep(.tiny-breadcrumb__inner:hover) {
				cursor: default;
				color: var(--ti-breadcrumb-text-color);
			}
			:deep(.tiny-breadcrumb .tiny-breadcrumb__item) {
				cursor: pointer;
			}
		}
	}
</style>
