import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { ZiggyVue } from '../../vendor/tightenco/ziggy'
import App from './App.vue'
import store from './stores'
import router from './routes'
import * as Icons from '@ant-design/icons-vue'

createInertiaApp({
    title: (title) => `${title} - ${import.meta.env.VITE_APP_NAME}`,
    resolve: (name) => resolvePageComponent(`./${name}.vue`, import.meta.glob('./**/*.vue')),
    setup({ el, App, props, plugin }) {
        const main = createApp({ render: () => h(App, props) })
        main.use(plugin)
        main.use(ZiggyVue)
        main.use(store)
        main.use(router)
        main.mount(el)
        Object.keys(Icons).forEach((key) => {
            main.component(`Ant${key}`, Icons[key])
        })
        return main
    },
    progress: {
        color: '#4B5563'
    }
})
