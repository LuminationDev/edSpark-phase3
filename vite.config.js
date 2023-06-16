import { defineConfig } from 'vite';
import { fileURLToPath, URL } from 'url';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';


export default defineConfig({
    build: {
        outDir: './public/build',
    },
    plugins: [
        vue(),
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/output.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '@': fileURLToPath(new URL('./resources', import.meta.url))
        }
    },
    optimizeDeps: {
        include: [
            "vue-google-maps-community-fork",
            "fast-deep-equal",
        ],
    },
    esbuild: {
        drop: ['console', 'debugger'],
    },
});
