import { createPinia } from 'pinia'
import { createPersistedState } from 'pinia-plugin-persistedstate'
import localforage from 'localforage'

const pinia = createPinia()
pinia.use(createPersistedState({
	storage: window.localStorage
}))

export default pinia
