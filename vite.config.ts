import path from 'path';
import { defineConfig, loadEnv } from 'vite';
import react from '@vitejs/plugin-react';

export default defineConfig(({ mode }) => {
    const env = loadEnv(mode, '.', '');
    return {
      base: '/EH-Hero/',
      server: {
        port: 3000,
        host: '0.0.0.0',
        proxy: {
          '/EH-Hero/EH-PORTAL-IT-ADMIN': {
            target: 'http://127.0.0.1:8000',
            changeOrigin: false,
          },
          '/EH-PORTAL-IT-ADMIN': {
            target: 'http://127.0.0.1:8000',
            changeOrigin: false,
          },
          '/EH-Hero/api': {
            target: 'http://127.0.0.1:8000',
            changeOrigin: false,
            rewrite: (path) => path.replace(/^\/EH-Hero/, ''),
          },
          '/api': {
            target: 'http://127.0.0.1:8000',
            changeOrigin: false,
          },
          '/storage': {
            target: 'http://127.0.0.1:8000',
            changeOrigin: false,
          },
        },
      },
      plugins: [
        react(),
        {
          name: 'not-found-urls-handler',
          configureServer(server) {
            server.middlewares.use((req, res, next) => {
              const rawUrl = req.url || '';
              const url = rawUrl.split('?')[0];

              // Allow Vite internal dev assets, scripts, and API proxies
              if (
                url.startsWith('/@') ||
                url.startsWith('/src') ||
                url.startsWith('/node_modules') ||
                url.startsWith('/assets') ||
                url.startsWith('/public') ||
                url.startsWith('/api') ||
                url.startsWith('/storage') ||
                url.startsWith('/EH-PORTAL-IT-ADMIN')
              ) {
                return next();
              }

              // Root path redirects to main app
              if (url === '/' || url === '') {
                res.writeHead(302, { Location: '/EH-Hero/' });
                return res.end();
              }

              // Any request that does NOT start with base (/EH-Hero) is a not-found URL (e.g. /admin/login)
              if (!url.startsWith('/EH-Hero')) {
                res.writeHead(302, { Location: '/EH-Hero/#404' });
                return res.end();
              }

              next();
            });
          },
        },
      ],
      define: {
        'process.env.API_KEY': JSON.stringify(env.GEMINI_API_KEY),
        'process.env.GEMINI_API_KEY': JSON.stringify(env.GEMINI_API_KEY),
        'process.env.FASHN_API_KEY': JSON.stringify(env.FASHN_API_KEY),
        global: 'globalThis',
      },
      resolve: {
        alias: {
          '@': path.resolve(__dirname, '.'),
          buffer: 'buffer/',
        }
      },
      build: {
        rollupOptions: {
          output: {
            manualChunks: {
              'vendor-react':  ['react', 'react-dom'],
              'vendor-gemini': ['@google/genai'],
            },
          },
        },
      },
      optimizeDeps: {
        include: ['buffer'],
      }
    };
});
