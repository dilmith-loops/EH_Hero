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
      plugins: [react()],
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
