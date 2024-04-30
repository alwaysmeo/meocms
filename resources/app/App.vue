<script setup>
import zhCN from 'ant-design-vue/es/locale/zh_CN'
import dayjs from 'dayjs'
import 'dayjs/locale/zh-cn'

dayjs.locale('zh-cn')
const theme = reactive({
    token: {
        colorText: '#272b32',
        colorTextHeading: '#272b32',
        colorIconHover: '#272b32'
    }
})
</script>

<template>
    <a-config-provider :locale="zhCN" :theme="theme">
        <router-view />
    </a-config-provider>
</template>

<style lang="scss">
/** */
</style>
