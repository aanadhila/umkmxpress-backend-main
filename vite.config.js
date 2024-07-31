import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
                'public/assets/css/style.bundle.css',
                'public/assets/js/scripts.bundle.js',
                'public/assets/plugins/global/plugins.bundle.css',
                'public/assets/plugins/global/plugins.bundle.js',
                'public/assets/js/widgets.bundle.js',
                'public/assets/js/custom/widgets.js'
            ],
            refresh: true,
        }),
    ],
});
