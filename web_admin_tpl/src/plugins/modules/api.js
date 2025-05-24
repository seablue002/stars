/*
 * 自动全局挂载api
 */
import mock from '@/mock'
import api from '@/api'
import { API_CODE } from '@/settings/config/http'

// 本地开发模式下启用mock，开发可以根据实际情况手动调整
mock(import.meta.env.VITE_APP_COMMAND === 'dev')

const apiPlugin = {
  install(app) {
    app.config.globalProperties.$api = api

    app.config.globalProperties.$apiCode = API_CODE
  },
}
export default apiPlugin
