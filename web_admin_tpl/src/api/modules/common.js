import { http } from '@/utils/http'
import { API_VERSION } from '@/settings/config/http'

// 获取系统信息
export const systemInfoApi = async () => {
  const url = `${API_VERSION}admin/system-info`
  return http.get(url)
}

export const systemConfigApi = async (params) => {
  const url = `${API_VERSION}admin/system-config`
  return http.get(url, { params })
}

/**
 * 根据字典类型key获取字典列表
 * @param params
 * @returns
 */
export const dicListApi = async (params) => {
  const url = `${API_VERSION}admin/dic-list`
  return http.get(url, { params })
}
