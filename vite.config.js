import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/content-manager.js',
                'resources/js/data-user.js',
                'resources/js/robot-control.js',
                'resources/js/course-theme.js',
                'resources/js/content.js',
                'resources/js/table-navbar.js',
                'resources/js/content-feature.js',
            ],
            refresh: true,
        }),
    ],
});
