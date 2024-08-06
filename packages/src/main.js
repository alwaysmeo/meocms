import { createApp } from 'vue'
import App from './App.vue'
import stores from './stores/pinia'
import routes from './routes'
import language from './language'
import * as Icons from '@ant-design/icons-vue'

const app = createApp(App)
app.use(stores)
app.use(routes)
app.use(language)
app.mount('#app')
Object.keys(Icons).forEach((key) => {
	app.component(`Ant${key}`, Icons[key])
})
