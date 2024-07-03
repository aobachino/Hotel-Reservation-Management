import laravel from 'laravel-vite-plugin';
import path from 'path';
import { defineConfig } from 'vite';
import viteCompression from 'vite-plugin-compression';

export default defineConfig({
  resolve: {
    alias: {
      '@': path.resolve(__dirname, './src'),
    },
  },
  plugins: [
    laravel({
      input: [
        'resources/sass/app.scss',
        'resources/js/app.js',
      ],
      outputDir: 'public/build', // Ensure outputDir is correctly set
      refresh: true,
    }),
    viteCompression(),
  ],
});
