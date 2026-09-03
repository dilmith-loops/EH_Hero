import path from 'path';
import { defineConfig, loadEnv } from 'vite';
import react from '@vitejs/plugin-react';

export default defineConfig(({ mode }) => {
    const env = loadEnv(mode, '.', '');
    return {
      base: '/fiton/',
      server: {
        port: 3000,
        host: '0.0.0.0',
        // Proxy /api calls to Laragon's PHP server during local development.
        // Change the target if your Laragon site is at a different URL.
        proxy: {
          '/fiton/api': {
            target: 'http://localhost',
            changeOrigin: true,
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
