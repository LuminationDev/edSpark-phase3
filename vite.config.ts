import vue from '@vitejs/plugin-vue';
import laravel, {refreshPaths} from 'laravel-vite-plugin';
import * as path from "path";
import {fileURLToPath, URL} from 'url';
import {defineConfig} from 'vite';


export default defineConfig({
    server: {https:true},
    build: {
        outDir: './public/build',
        commonjsOptions: {
            exclude: ['ckeditor5-custom-build']
        }
    },
    plugins: [
        vue({
            template: {
                compilerOptions: {
                    isCustomElement: tag => tag === 'trix-editor' || tag === 'ckeditor',

                }
            }
        }),
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/output.css',
                'resources/js/app.ts',
                'resources/css/filament/admin/theme.css'
            ],
            refresh: [
                ...refreshPaths,
                'app/Livewire/**',
            ],
        }),
    ],
    resolve: {
        alias: {
            '@': fileURLToPath(new URL('./resources', import.meta.url)),
            '~trix': path.resolve(__dirname, 'node_modules/trix'),
        }
    },
    optimizeDeps: {
        include: [
            "vue-google-maps-community-fork",
            "fast-deep-equal",
        ],
    },
    esbuild: {
        // drop: ['console', 'debugger'],
    },
});
