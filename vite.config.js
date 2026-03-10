import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import { VitePWA } from 'vite-plugin-pwa';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
        VitePWA({
            registerType: 'autoUpdate',
            injectRegister: false,
            strategies: 'injectManifest',
            srcDir: 'resources/js',
            filename: 'sw.js',
            injectManifest: {
                globPatterns: ['**/*.{js,css,html,ico,png,svg,woff,woff2}'],
            },
            manifest: {
                name: 'SIM Penugasan Kuliah',
                short_name: 'SIM Tugas',
                description: 'Sistem Informasi Manajemen Penugasan Kuliah',
                theme_color: '#1e40af',
                background_color: '#ffffff',
                display: 'standalone',
                scope: '/',
                start_url: '/',
                icons: [
                    {
                        src: '/icons/icon-72x72.png',
                        sizes: '72x72',
                        type: 'image/png'
                    },
                    {
                        src: '/icons/icon-96x96.png',
                        sizes: '96x96',
                        type: 'image/png'
                    },
                    {
                        src: '/icons/icon-128x128.png',
                        sizes: '128x128',
                        type: 'image/png'
                    },
                    {
                        src: '/icons/icon-144x144.png',
                        sizes: '144x144',
                        type: 'image/png'
                    },
                    {
                        src: '/icons/icon-152x152.png',
                        sizes: '152x152',
                        type: 'image/png'
                    },
                    {
                        src: '/icons/icon-192x192.png',
                        sizes: '192x192',
                        type: 'image/png'
                    },
                    {
                        src: '/icons/icon-384x384.png',
                        sizes: '384x384',
                        type: 'image/png'
                    },
                    {
                        src: '/icons/icon-512x512.png',
                        sizes: '512x512',
                        type: 'image/png'
                    },
                    {
                        src: '/icons/icon-512x512.png',
                        sizes: '512x512',
                        type: 'image/png',
                        purpose: 'maskable'
                    }
                ],
                screenshots: [
                    {
                        src: '/icons/icon-512x512.png',
                        sizes: '512x512',
                        type: 'image/png',
                        form_factor: 'wide',
                        label: 'SIM Penugasan Kuliah Desktop'
                    },
                    {
                        src: '/icons/icon-512x512.png',
                        sizes: '512x512',
                        type: 'image/png',
                        form_factor: 'narrow',
                        label: 'SIM Penugasan Kuliah Mobile'
                    }
                ]
            }
        })
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
