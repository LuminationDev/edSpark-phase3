import { defineConfig } from 'vite';
import { fileURLToPath, URL } from 'url';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

import { createProxyMiddleware } from 'http-proxy-middleware';


export default defineConfig({
    plugins: [
        vue(),
        laravel({
            input: [
                // 'resources/css/app.css',
                'resources/js/app.js'
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
    server: {
        middleware: [
            createProxyMiddleware('/api', {
                target: 'https://maps.googleapis.com',
                changeOrigin: true,
                pathRewrite: {
                    '^/api': '/maps/api'
                },
                onProxyReq: (proxyReq, req, res) => {
                    console.log(`Proxying request to ${req.url}`);
                },
                onError: (err, req, res) => {
                    console.error(err);
                    res.writeHead(500, {
                        'Content-Type': 'text/plain'
                    });
                    res.end('Proxy error');
                }
            })
        ]
    }
});
