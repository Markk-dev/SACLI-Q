import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/jquery.js'],
            refresh: true,
        }),
    ],   
    input: [
        // rest of your inputs
        'resources/js/app.js',
        'resources/js/echo.js',
        'resources/js/jquery.js',
    ],
});
