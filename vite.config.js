import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/content/editor.js',
                'resources/js/modules/module-manager.js',
                'resources/js/content/content-manager.js',
                'resources/js/content/style.js',
                'resources/js/data-user.js',
                'resources/js/robot-control.js',
                'resources/js/course-theme.js',
                'resources/js/table-navbar.js',
                'resources/js/content-feature.js',
                'resources/js/theme.js',
                'resources/js/course/show.js',
                'resources/js/profile/profile-images.js',
                'resources/js/profile/added-social-link.js',
                'resources/js/settings/appearance.js',
            ],
            refresh: true,
        }),
    ],
});
