import { resolve } from 'path'
import { defineConfig, loadEnv } from 'vite'
import { visualizer } from 'rollup-plugin-visualizer'
import vue from '@vitejs/plugin-vue'
import vueJsx from '@vitejs/plugin-vue-jsx'
import viteCompression from 'vite-plugin-compression'
import viteAutoImport from 'unplugin-auto-import/vite'
import viteComponents from 'unplugin-vue-components/vite'
import viteRemoveConsole from 'vite-plugin-remove-console'

export default defineConfig(({ mode }) => {
	const { VITE_HOST, VITE_BASE_URL, VITE_PORT, VITE_OUTDIR, VITE_API_DOMAIN } = loadEnv(mode, __dirname)
	console.log(loadEnv(mode, __dirname))

	return {
		base: VITE_BASE_URL,
		define: {
			'process.env': { ...process.env, TINY_MODE: 'pc' }
		},
		plugins: [
			vue(),
			vueJsx(),
			viteRemoveConsole({
				externalValue: ['version']
			}),
			viteAutoImport({
				imports: ['vue', 'vue-router'],
				eslintrc: {
					enabled: false,
					filepath: './.eslintrc-auto-import.json',
					globalsPropValue: true
				}
			}),
			viteComponents({
				directoryAsNamespace: true,
				resolvers: [
					(name) => {
						if (name.startsWith('Tiny') && !name.startsWith('TinyIcon')) {
							return {
								name: name.slice(4),
								from: '@opentiny/vue'
							}
						}
						if (name.startsWith('Meo')) return { name: name.slice(3), from: '@components/common' }
					}
				]
			}),
			viteCompression({
				viteCompression: 'gzip',
				threshold: 2048,
				deleteOriginFile: false
			}),
			visualizer({ open: true })
		],
		chainWebpack: (config) => {
			config.resolve.alias.set('@opentiny/vue-theme', '@opentiny/vue-theme/aurora-theme')
		},
		resolve: {
			alias: [
				{
					find: '@',
					replacement: resolve(__dirname, 'src')
				},
				{
					find: '@apis',
					replacement: resolve(__dirname, 'src/apis')
				},
				{
					find: '@views',
					replacement: resolve(__dirname, 'src/views')
				},
				{
					find: '@assets',
					replacement: resolve(__dirname, 'src/assets')
				},
				{
					find: '@stores',
					replacement: resolve(__dirname, 'src/stores')
				},
				{
					find: '@components',
					replacement: resolve(__dirname, 'src/components')
				},
				{
					find: '@hooks',
					replacement: resolve(__dirname, 'src/hooks')
				},
				{
					find: '@utils',
					replacement: resolve(__dirname, 'src/utils')
				},
				{
					find: '@routes',
					replacement: resolve(__dirname, 'src/routes')
				},
				{
					find: 'vue',
					replacement: 'vue/dist/vue.esm-bundler.js'
				},
				{
					find: /\@opentiny\/vue-theme\/(?!(aurora))/,
					replacement: '@opentiny/vue-theme/aurora-theme/'
				}
			]
		},
		css: {
			preprocessorOptions: {
				scss: {
					additionalData: `@import '@assets/styles/index.scss';`,
					charset: false,
					outputStyle: 'compressed'
				}
			}
		},
		build: {
			outDir: VITE_OUTDIR,
			chunkSizeWarningLimit: 500,
			minify: true,
			cssCodeSplit: true,
			rollupOptions: {
				output: {
					entryFileNames: 'js/[name].[hash].js',
					chunkFileNames: 'js/[name].[hash].js',
					assetFileNames: '[ext]/[name].[hash].[ext]',
					manualChunks(path) {
						if (path.includes('.pnpm')) {
							return path.match(/\.pnpm\/.+?(?=@)/)[0].replace(/\.pnpm\/@*/, '')
						} else if (path.includes('node_modules')) {
							return path.match(/node_modules\/.+?(?=\/)/)[0].replace(/node_modules\/@*/, '')
						}
					}
				}
			}
		},
		server: {
			host: VITE_HOST,
			port: VITE_PORT,
			open: false,
			hmr: true,
			proxy: {
				'/api': {
					target: VITE_API_DOMAIN,
					changeOrigin: true,
					rewrite: (path) => path.replace(/^\/api/, '')
				}
			}
		}
	}
})
