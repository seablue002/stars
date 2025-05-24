/*
 * NTAxios类，axios二次封装类
 */
import axios from 'axios'
import { isFunction } from '@/utils/helper/is'
import {
  requestInterceptorMiddleware,
  requestErrorMiddleware,
  responseInterceptorMiddleware,
  responseErrorMiddleware,
} from './axiosMiddleware'

class NTAxios {
  /**
   * NTAxios构造函数
   * @param {Object} config NTAxios实例化参数 {
   *  ...axios对应的所有支持config参数,
   * 业务实际自定义额外参数
   *
   * 请求拦截中间件handler
   * requestInterceptorHandler,
   *
   * 请求错误处理中间件handler
   * requestErrorHandler
   *
   * 响应拦截中间件handler
   * responseInterceptorHandler
   *
   * 响应错误处理中间件handler
   * responseErrorHandler
   * }
   */
  constructor(config) {
    // 包含axios需要使用的config参数以及NTAxios实现相关业务需要的配置参数
    this.config = config

    // axios实例
    this.createAxios(config)
  }

  /**
   * 创建axios实例
   * @param {Object} config axios实例化参数
   */
  createAxios(config) {
    this.axiosInstance = axios.create(config)

    this.registMiddleware()
  }

  /**
   * 设置请求头headers
   * @param {Object} headers 请求头
   */
  setHeaders(headers) {
    this.axiosInstance.defaults.headers = {
      ...this.axiosInstance.defaults.headers,
      headers,
    }
  }

  /**
   * 注册中间件
   */
  registMiddleware() {
    // axios中间件注册
    this.registRequestMiddleware()
    this.registResponseMiddleware()
  }

  /**
   * 注册请求拦截中间件
   */
  registRequestMiddleware() {
    // 请求拦截中间件注册[请求拦截中间件注册, 请求错误处理中间件注册]
    const requestInterceptorUseParams = []
    // 请求拦截中间件注册
    if (
      this.config.requestInterceptorHandler &&
      isFunction(this.config.requestInterceptorHandler)
    ) {
      requestInterceptorUseParams.push((config) => {
        requestInterceptorMiddleware(
          this.axiosInstance,
          config,
          this.config.requestInterceptorHandler
        )

        // 返回axios请求config
        return config
      })
    }

    // 请求错误处理中间件注册
    if (
      this.config.requestErrorHandler &&
      isFunction(this.config.requestErrorHandler)
    ) {
      requestInterceptorUseParams.push((error) => {
        requestErrorMiddleware(error, this.config.requestErrorHandler)
      })
    }
    this.axiosInstance.interceptors.request.use(...requestInterceptorUseParams)
  }

  /**
   * 注册响应拦截中间件
   */
  registResponseMiddleware() {
    // 响应拦截中间件注册[请求拦截中间件注册, 请求错误处理中间件注册]
    const responseInterceptorUseParams = []
    // 响应拦截中间件注册
    if (
      this.config.responseInterceptorHandler &&
      isFunction(this.config.responseInterceptorHandler)
    ) {
      responseInterceptorUseParams.push((response) => {
        responseInterceptorMiddleware(
          this.axiosInstance,
          response,
          this.config.responseInterceptorHandler
        )

        // 返回axios请求响应response
        return response
      })
    }

    // 响应错误处理中间件注册
    if (
      this.config.responseErrorHandler &&
      isFunction(this.config.responseErrorHandler)
    ) {
      responseInterceptorUseParams.push((error) => {
        return responseErrorMiddleware(error, this.config.responseErrorHandler)
      })
    }

    this.axiosInstance.interceptors.response.use(
      ...responseInterceptorUseParams
    )
  }
}

export default NTAxios
