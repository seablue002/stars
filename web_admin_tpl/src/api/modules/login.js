import { http } from '@/utils/http'
import { API_VERSION } from '@/settings/config/http'

// 系统用户登录接口
export const loginApi = async (data) => {
  const url = `${API_VERSION}admin/login-in`
  return http.post(url, data)
}

export const getLoginCaptchaApi = async () => {
  const url = `${API_VERSION}admin/verify-img`
  return http.get(url)
}

/**
 * 退出登录
 * @returns
 */
export const logoutApi = async () => {
  const url = `${API_VERSION}admin/login-out`
  return http.post(url)
}
