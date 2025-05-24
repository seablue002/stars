import { http } from '@/utils/http'
import { API_VERSION } from '@/settings/config/http'

/**
 * 获取分类列表
 * @returns
 */
export const systemConfigCategoryListApi = async () => {
  const url = `${API_VERSION}admin/system-config-category-list`
  return http.get(url)
}

/**
 * 获取分类列表by rid
 * @returns
 */
export const systemConfigCategoryListByRidApi = async (params) => {
  const url = `${API_VERSION}admin/system-config-category-list-by-rid`
  return http.get(url, { params })
}

/**
 * [addSystemConfigCategory 添加系统配置分类]
 * @param {[type]} params [description]
 */
export const addSystemConfigCategoryApi = (data) => {
  const url = `${API_VERSION}admin/add-system-config-category`
  return http.post(url, data)
}

/**
 * [editSystemConfigCategory 编辑系统配置分类]
 * @param {[type]} params [description]
 */
export const editSystemConfigCategoryApi = (data) => {
  const url = `${API_VERSION}admin/edit-system-config-category`
  return http.post(url, data)
}

/**
 * 系统配置分类详情
 * @param params
 * @returns
 */
export const systemConfigCategoryDetailApi = async (params) => {
  const url = `${API_VERSION}admin/system-config-category-detail`
  return http.get(url, { params })
}

/**
 * 删除系统配置分类
 * @param params
 * @returns
 */
export const deleteSystemConfigCategoryApi = async (params) => {
  const url = `${API_VERSION}admin/delete-system-config-category`
  return http.get(url, { params })
}
