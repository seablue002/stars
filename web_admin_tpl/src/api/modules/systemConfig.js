import { http } from '@/utils/http'
import { API_VERSION } from '@/settings/config/http'

/**
 * 获取配置选项列表
 * @returns
 */
export const systemConfigListApi = async (params) => {
  const url = `${API_VERSION}admin/system-config-list`
  return http.get(url, { params })
}

export const systemConfigListByCidApi = async (params) => {
  const url = `${API_VERSION}admin/system-config-list-by-cid`
  return http.get(url, { params })
}

/**
 * [addSystemConfig 添加配置选项]
 * @param {[type]} params [description]
 */
export const addSystemConfigApi = (data) => {
  const url = `${API_VERSION}admin/add-system-config`
  return http.post(url, data)
}

/**
 * [editSystemConfig 编辑配置选项]
 * @param {[type]} params [description]
 */
export const editSystemConfigApi = (data) => {
  const url = `${API_VERSION}admin/edit-system-config`
  return http.post(url, data)
}

/**
 * 配置选项详情
 * @param params
 * @returns
 */
export const systemConfigDetailApi = async (params) => {
  const url = `${API_VERSION}admin/system-config-detail`
  return http.get(url, { params })
}

/**
 * 删除配置选项
 * @param params
 * @returns
 */
export const deleteSystemConfigApi = async (params) => {
  const url = `${API_VERSION}admin/delete-system-config`
  return http.get(url, { params })
}

export const settingSaveSystemConfigApi = (data) => {
  const url = `${API_VERSION}admin/setting-save-system-config`
  return http.post(url, data)
}
