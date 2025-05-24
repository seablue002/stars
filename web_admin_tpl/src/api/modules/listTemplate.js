import { http } from '@/utils/http'
import { API_VERSION } from '@/settings/config/http'

/**
 * 获取列表模板列表
 * @returns
 */
export const tplListApi = async (params) => {
  const url = `${API_VERSION}admin/list-tpl-list`
  return http.get(url, { params })
}

/**
 * 列表模板基本信息列表
 * @returns
 */
export const listTplBaseInfoListApi = async () => {
  const url = `${API_VERSION}admin/list-tpl-base-info-list`
  return http.get(url)
}

export const tplListByCidApi = async (params) => {
  const url = `${API_VERSION}admin/list-tpl-list-by-cid`
  return http.get(url, { params })
}

/**
 * [addTpl 添加列表模板]
 * @param {[type]} params [description]
 */
export const addTplApi = (data) => {
  const url = `${API_VERSION}admin/add-list-tpl`
  return http.post(url, data)
}

/**
 * [editTpl 编辑列表模板]
 * @param {[type]} params [description]
 */
export const editTplApi = (data) => {
  const url = `${API_VERSION}admin/edit-list-tpl`
  return http.post(url, data)
}

/**
 * 列表模板详情
 * @param params
 * @returns
 */
export const tplDetailApi = async (params) => {
  const url = `${API_VERSION}admin/list-tpl-detail`
  return http.get(url, { params })
}

/**
 * 删除列表模板
 * @param params
 * @returns
 */
export const deleteTplApi = async (params) => {
  const url = `${API_VERSION}admin/delete-list-tpl`
  return http.get(url, { params })
}
