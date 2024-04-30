import { resolve } from 'path'
import { defineConfig, loadEnv } from 'vite'
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig(({ mode }) => {
    const {
        VITE_HOT,
        VITE_HOST,
        VITE_BASE_URL,
        VITE_PORT,
        VITE_OUTDIR,
        VITE_API_DOMAIN
    } = loadEnv(mode, __dirname)
    console.log(loadEnv(mode, __dirname))

    return {
        plugins: [
            laravel({
                input: 'resources/js/app.js',
                refresh: true
            }),
            vue()
        ],
        server: {
            host: VITE_HOST,
            port: VITE_PORT,
            open: false,
            hmr: VITE_HOT === 'true',
            // proxy: {
            //     '/api': {
            //         target: VITE_API_DOMAIN,
            //         changeOrigin: true,
            //         rewrite: (path) => path.replace(/^\/api/, '')
            //     }
            // }
        }
    }
})

