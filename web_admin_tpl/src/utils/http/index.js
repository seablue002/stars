/*
 * http实例主文件
 */
import useUserStore from '@/store/modules/user'
import { XHR_TIMEOUT } from '@/settings/config/http'
import router from '@/router'
import NTAxios from './NTAxios'

// 请求拦截器函数
function requestInterceptorHandler(axiosInstance, requestConfig) {
  const userStore = useUserStore()
  requestConfig.headers.Authorization = userStore.token
}

// 响应拦截器函数
function responseInterceptorHandler(instance, response) {
  const { status, data } = response.data
  if (status === 400 && data === 'need-login') {
    const userStore = useUserStore()
    userStore.clear()

    router.push('/login')
  }
}

// 响应错误处理
function responseErrorHandler(error) {
  return {
    code: error.code,
    message: error.message,
    data: {
      status: 500,
      message: error.message,
      data: null,
    },
  }
}

// http请求对象，由NTAxios封装类生成，为axios实例
export const http = new NTAxios({
  baseURL: import.meta.env.VITE_APP_API_BASE_URL,
  // 毫秒数值
  timeout: XHR_TIMEOUT,
  requestInterceptorHandler,
  responseInterceptorHandler,
  responseErrorHandler,
}).axiosInstance

// 其他url请求调用的axios实例, 可以根据具体请求特性进行多个实例生成
export const otherHttp = new NTAxios({
  // 具体业务需要，进行具体特性化参数配置
})
