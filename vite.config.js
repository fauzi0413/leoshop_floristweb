import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js'],
      refresh: true,
    }),
  ],
  server: {
    host: true,          // 0.0.0.0
    port: 5173,
    strictPort: true,
    hmr: {
      host: '10.169.6.124', // <-- ganti dengan IP PC kamu
      port: 5173,
    },
  },
});
