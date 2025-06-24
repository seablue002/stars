/*
 * vite打包配置
 */
import { fileURLToPath, URL } from 'node:url'

import { defineConfig, loadEnv } from 'vite'
import vue from '@vitejs/plugin-vue'
import eslint from 'vite-plugin-eslint'
import viteCompression from 'vite-plugin-compression'
import { createHtmlPlugin } from 'vite-plugin-html'
import { viteCommonjs } from '@originjs/vite-plugin-commonjs'

// https://vitejs.dev/config/
export default defineConfig((buildParsms) => {
  const { command, mode } = buildParsms
  // 环境变量
  const env = loadEnv(mode, './')

  const config = {
    base: '/admsystem/',
    build: {
      sourcemap: mode === 'development',
      minify: 'terser',
      terserOptions: {
        compress: {
          drop_console: command === 'build' && mode === 'production',
        },
      },
    },
    plugins: [
      vue(),
      eslint(),
      viteCompression({
        // 单位Bytes, 大于 10KB 的文件进行 gzip 压缩
        threshold: 10240,
      }),
      createHtmlPlugin({
        // 需要注入 index.html ejs 模版的数据
        inject: {
          data: {
            title: env.VITE_APP_TITLE,
          },
        },
      }),
      viteCommonjs({
        include: ['jsoneditor'],
      }),
    ],
    resolve: {
      alias: {
        '@': fileURLToPath(new URL('./src', import.meta.url)),
        '#ASSETS': fileURLToPath(new URL('./src/assets', import.meta.url)),
        '#LAYOUTS': fileURLToPath(new URL('./src/layouts', import.meta.url)),
      },
    },
    css: {
      postcss: {
        plugins: [require('tailwindcss'), require('autoprefixer')],
      },
    },
  }

  // 本地开发模式下启用proxy代理
  if (env.VITE_APP_COMMAND === 'dev') {
    // 本地开发 proxy代理服务器相关配置
    function formatLocalProxyOptions(env) {
      const envFileData = loadEnv(env, process.cwd())
      const localProxyOptions = {}

      JSON.parse(envFileData.VITE_APP_VITE_PROXY_KEYS).forEach((key) => {
        localProxyOptions[key] = {
          target: 'http://localhost:8080',
          changeOrigin: true,
          // rewrite: (path) => path.replace(/\/api/, '/api1'),
        }
      })

      return localProxyOptions
    }

    config.server = {
      proxy: formatLocalProxyOptions(mode),
    }
  }

  return config
})
