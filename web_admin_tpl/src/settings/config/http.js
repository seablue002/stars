/*
 * http相关配置
 */

export const API_HOST = import.meta.env.VITE_APP_API_BASE_URL

export const API_VERSION = ''

// api请求成功状态码
export const API_SUCCESS_CODE = 200

// api请求状态码集合
export const API_CODE = {
  SUCCESS: API_SUCCESS_CODE,
}

// api请求超时时长默认值，毫秒
export const XHR_TIMEOUT = 10000

// 服务器中的资源文件(图片)路径
export const RESOURCE_PATH = `${API_HOST}storage/`
