import { createApp } from 'vue'
import App from './App.vue'
import store from './stores'
import router from './router'
import Icons from '@opentiny/vue-icon'

const app = createApp(App)

app.use(store)
app.use(router)
app.mount('#app')
Object.keys(Icons).forEach((key) => {
	app.component(`Tiny${key}`, Icons[key]())
})
