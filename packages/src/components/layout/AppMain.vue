<script setup>
	const route = useRoute()
</script>

<template>
	<div class="main-app">
		<div class="breadcrumb">
			<tiny-breadcrumb>
				<tiny-breadcrumb-item
					v-for="(item, index) in route.matched.filter((item) => item.name)"
					:key="item.name"
					:to="index ? { name: item.name } : {}"
					:label="item.meta.title"
				/>
			</tiny-breadcrumb>
		</div>
		<router-view :key="route.path" v-slot="{ Component }">
			<transition appear name="fade-transform" mode="out-in">
				<keep-alive>
					<component :is="Component" />
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
		.breadcrumb {
			margin-bottom: 10px;
		}
		:deep(.tiny-breadcrumb .tiny-breadcrumb__item) {
			cursor: pointer;
			&:first-child {
				cursor: default;
				.tiny-breadcrumb__inner:hover {
					color: var(--ti-breadcrumb-text-color);
				}
			}
		}
	}
</style>
