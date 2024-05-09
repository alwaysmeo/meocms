import { resolve } from "path";
import { defineConfig, loadEnv } from "vite";
import { visualizer } from "rollup-plugin-visualizer";
import { AntDesignVueResolver } from "unplugin-vue-components/resolvers";
import vue from "@vitejs/plugin-vue";
import vueJsx from "@vitejs/plugin-vue-jsx";
import viteCompression from "vite-plugin-compression";
import viteAutoImport from "unplugin-auto-import/vite";
import viteComponents from "unplugin-vue-components/vite";
import viteRemoveConsole from "vite-plugin-remove-console";
import viteLaravel from "laravel-vite-plugin";

export default defineConfig(({ mode }) => {
    const {
        VITE_HOST,
        VITE_BASE_URL,
        VITE_PORT,
        VITE_OUTDIR,
        VITE_API_DOMAIN,
    } = loadEnv(mode, __dirname);
    console.log(loadEnv(mode, __dirname));

    return {
        base: VITE_BASE_URL,
        plugins: [
            vue({
                template: {
                    transformAssetUrls: {
                        base: null,
                        includeAbsolute: false,
                    },
                },
            }),
            vueJsx(),
            viteRemoveConsole({
                externalValue: ["version"],
            }),
            viteAutoImport({
                imports: ["vue"],
                eslintrc: {
                    enabled: false,
                    filepath: "resources/app/.eslintrc-auto-import.json",
                    globalsPropValue: true,
                },
            }),
            viteComponents({
                directoryAsNamespace: true,
                resolvers: [
                    AntDesignVueResolver({ importStyle: false }),
                    (name) => {
                        if (name.startsWith("Meo"))
                            return {
                                name: name.slice(3),
                                from: "@components/common",
                            };
                    },
                ],
            }),
            viteCompression({
                viteCompression: "gzip",
                threshold: 2048,
                deleteOriginFile: false,
            }),
            visualizer({ open: true }),
            viteLaravel({
                input: "resources/app/main.js",
                refresh: true,
            }),
        ],
        resolve: {
            alias: {
                "@": resolve(__dirname, "resources/app"),
                "@apis": resolve(__dirname, "resources/app/apis"),
                "@views": resolve(__dirname, "resources/app/views"),
                "@assets": resolve(__dirname, "resources/app/assets"),
                "@stores": resolve(__dirname, "resources/app/stores"),
                "@components": resolve(__dirname, "resources/app/components"),
                "@hooks": resolve(__dirname, "resources/app/hooks"),
                "@utils": resolve(__dirname, "resources/app/utils"),
                "@routes": resolve(__dirname, "resources/app/router"),
                vue: "vue/dist/vue.esm-bundler.js",
            },
        },
        css: {
            preprocessorOptions: {
                scss: {
                    additionalData: `@import '@assets/styles/index.scss';`,
                    charset: false,
                    outputStyle: "compressed",
                },
            },
        },
        build: {
            outDir: VITE_OUTDIR,
            chunkSizeWarningLimit: 500,
            minify: true,
            cssCodeSplit: true,
            rollupOptions: {
                output: {
                    entryFileNames: "js/[name].[hash].js",
                    chunkFileNames: "js/[name].[hash].js",
                    assetFileNames: "[ext]/[name].[hash].[ext]",
                    manualChunks(path) {
                        if (path.includes(".pnpm")) {
                            return path
                                .match(/\.pnpm\/.+?(?=@)/)[0]
                                .replace(/\.pnpm\/@*/, "");
                        } else if (path.includes("node_modules")) {
                            return path
                                .match(/node_modules\/.+?(?=\/)/)[0]
                                .replace(/node_modules\/@*/, "");
                        }
                    },
                },
            },
        },
        server: {
            host: "0.0.0.0",
            port: VITE_PORT,
            open: false,
            hmr: {
                host: VITE_HOST,
            },
            proxy: {
                "/api": {
                    target: VITE_API_DOMAIN,
                    changeOrigin: true,
                    rewrite: (path) => path.replace(/^\/api/, ""),
                },
            },
        },
    };
});
