import { createApp } from 'vue'
import App from './App.vue'
import store from './stores'
import router from './router'
import * as Icons from '@ant-design/icons-vue'

const main = createApp(App)

main.use(store)
main.use(router)
main.mount('#main')
Object.keys(Icons).forEach((key) => {
    main.component(`Ant${key}`, Icons[key])
})
