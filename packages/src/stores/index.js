import { createPinia } from 'pinia'
import piniaPersistedstate from 'pinia-plugin-persistedstate'

const store = createPinia()
store.use(piniaPersistedstate)

export default store
