/*
 * axios相关处理所需的中间件封装集合
 */

import { isFunction, isArray } from '@/utils/helper/is'

// 中间件通用handler wrap函数
function middlewareHandler(params) {
  if (!params || !params.handler) {
    throw new Error('缺少axios中间件handler')
  }
  if (isFunction(params.handler)) {
    if (params.handlerParams) {
      if (params.expandParams && isArray(params.handlerParams)) {
        return params.handler(...params.handlerParams)
      }
      return params.handler(params.handlerParams)
    }
    return params.handler()
  }
  return null
}

/**
 * 请求前置中间件
 * @param {*} axiosInstance 当前请求对应的axios实例
 * @param {*} handler 处理函数，放置具体业务实现
 */
export function requestBeforeMiddleware(axiosInstance, handler) {
  const params = {
    handlerParams: axiosInstance,
    handler,
  }
  middlewareHandler(params)
}

/**
 * 请求拦截中间件
 * @param {*} axiosInstance 当前请求对应的axios实例
 * @param {*} requestConfig 当前axios请求拦截传递的config
 * @param {*} handler 处理函数，放置具体业务实现
 */
export function requestInterceptorMiddleware(
  axiosInstance,
  requestConfig,
  handler
) {
  const params = {
    expandParams: true,
    handlerParams: [axiosInstance, requestConfig],
    handler,
  }
  middlewareHandler(params)
}

/**
 * 请求错误处理中间件
 * @param {*} error 当前axios请求拦截传递的error
 * @param {*} handler 处理函数，放置具体业务实现
 */
export function requestErrorMiddleware(error, handler) {
  const params = {
    handlerParams: error,
    handler,
  }
  return middlewareHandler(params)
}

/**
 * 响应拦截中间件
 * @param {*} axiosInstance 当前请求对应的axios实例
 * @param {*} response 当前axios请求拦截传递的response
 * @param {*} handler 处理函数，放置具体业务实现
 */
export function responseInterceptorMiddleware(
  axiosInstance,
  response,
  handler
) {
  const params = {
    expandParams: true,
    handlerParams: [axiosInstance, response],
    handler,
  }
  middlewareHandler(params)
}

/**
 * 响应错误处理中间件
 * @param {*} error 当前axios请求拦截传递的response
 * @param {*} handler 处理函数，放置具体业务实现
 */
export function responseErrorMiddleware(error, handler) {
  const params = {
    handlerParams: error,
    handler,
  }
  return middlewareHandler(params)
}
