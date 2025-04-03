import axios from 'axios'
import store from '@/store'
import router from '@/router'

import { isObject } from '@/utils'
import { API_HOST, API_TIME_OUT, HTTP_CONFIG } from '@/config/http'

const http = axios.create({
  baseURL: API_HOST,
  timeout: Number(API_TIME_OUT)
})

/* 请求拦截 */
http.interceptors.request.use(config => {
  // 统一带上用户登录态凭证X-Access-Token
  if (config.url !== 'api/login') {
    config.headers && (config.headers.Authorization = store.state.user.adminUserInfo.token)
  }
  return config
}, error => {
  return Promise.reject(error)
})

/* 响应拦截 */
http.interceptors.response.use(response => {
  if (isObject(response.data) && response.data.status === HTTP_CONFIG.API_ERROR_CODE) {
    if (isObject(response.data) && response.data.data === 'need-login') {
      // 登录状态已经过期，需要重新登录
      // 清空vuex、storage中的当前用户相关信息
      store.commit('clearAdminUserInfo')
      router.push('/login')
    }
  }
  return response.data
}, error => {
  let errorMessage
  if (error.message === 'Network Error') {
    errorMessage = { status: 0, message: '网络错误，接口无响应' }
  } else if (error.message.indexOf('timeout') === 0) {
    errorMessage = { status: 0, message: '网络错误，请求超时' }
  } else {
    errorMessage = { status: 0, message: error.message }
  }
  return errorMessage
})

export default http
