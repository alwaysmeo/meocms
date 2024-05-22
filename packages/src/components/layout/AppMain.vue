<script setup></script>

<template>
	<div class="main-app">
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
		<router-view v-slot="{ Component }">
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
		.main-breadcrumb {
			margin-bottom: 10px;
			.disabled :deep(.tiny-breadcrumb__inner:hover) {
				cursor: default;
				color: var(--ti-breadcrumb-text-color);
			}
		}
		:deep(.tiny-breadcrumb .tiny-breadcrumb__item) {
			cursor: pointer;
		}
	}
</style>
