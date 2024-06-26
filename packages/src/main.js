import { createApp } from 'vue'
import App from './App.vue'
import stores from './stores'
import routes from './routes'
import language from './language'
import Icons from '@opentiny/vue-icon'

const app = createApp(App)
app.use(stores)
app.use(routes)
app.use(language)
app.mount('#app')
Object.keys(Icons).forEach((key) => {
	app.component(`Tiny${key}`, Icons[key]())
})
