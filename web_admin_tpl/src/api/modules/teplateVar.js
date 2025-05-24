import { http } from '@/utils/http'
import { API_VERSION } from '@/settings/config/http'

/**
 * 获取模板变量列表
 * @returns
 */
export const tplVarListApi = async (params) => {
  const url = `${API_VERSION}admin/tpl-var-list`
  return http.get(url, { params })
}

/**
 * [addTplVarApi 添加模板变量]
 * @param {[type]} params [description]
 */
export const addTplVarApi = (data) => {
  const url = `${API_VERSION}admin/add-tpl-var`
  return http.post(url, data)
}

/**
 * [editTplVarApi 编辑模板变量]
 * @param {[type]} params [description]
 */
export const editTplVarApi = (data) => {
  const url = `${API_VERSION}admin/edit-tpl-var`
  return http.post(url, data)
}

/**
 * 模板变量详情
 * @param params
 * @returns
 */
export const tplVarDetailApi = async (params) => {
  const url = `${API_VERSION}admin/tpl-var-detail`
  return http.get(url, { params })
}

/**
 * 删除模板变量
 * @param params
 * @returns
 */
export const deleteTplVarApi = async (params) => {
  const url = `${API_VERSION}admin/delete-tpl-var`
  return http.get(url, { params })
}
