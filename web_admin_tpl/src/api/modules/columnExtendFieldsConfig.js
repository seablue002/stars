import { http } from '@/utils/http'
import { API_VERSION } from '@/settings/config/http'

/**
 * 获取栏目自定义字段配置选项列表
 * @returns
 */
export const columnExtendFieldsConfigListApi = async (params) => {
  const url = `${API_VERSION}admin/column-extend-fields-config-list`
  return http.get(url, { params })
}

/**
 * 获取栏目自定义字段配置选项全部列表
 * @returns
 */
export const columnExtendFieldsConfigAllListApi = async () => {
  const url = `${API_VERSION}admin/column-extend-fields-config-all-list`
  return http.get(url)
}

/**
 * [addColumnExtendFieldsConfig 添加栏目自定义字段配置选项]
 * @param {[type]} params [description]
 */
export const addColumnExtendFieldsConfigApi = (data) => {
  const url = `${API_VERSION}admin/add-column-extend-fields-config`
  return http.post(url, data)
}

/**
 * [editColumnExtendFieldsConfig 编辑栏目自定义字段配置选项]
 * @param {[type]} params [description]
 */
export const editColumnExtendFieldsConfigApi = (data) => {
  const url = `${API_VERSION}admin/edit-column-extend-fields-config`
  return http.post(url, data)
}

/**
 * 栏目自定义字段配置选项详情
 * @param params
 * @returns
 */
export const columnExtendFieldsConfigDetailApi = async (params) => {
  const url = `${API_VERSION}admin/column-extend-fields-config-detail`
  return http.get(url, { params })
}

/**
 * 删除栏目自定义字段配置选项
 * @param params
 * @returns
 */
export const deleteColumnExtendFieldsConfigApi = async (params) => {
  const url = `${API_VERSION}admin/delete-column-extend-fields-config`
  return http.get(url, { params })
}
