import { http } from '@/utils/http'
import { API_VERSION } from '@/settings/config/http'

/**
 * 获取详情模板列表
 * @returns
 */
export const tplListApi = async (params) => {
  const url = `${API_VERSION}admin/detail-tpl-list`
  return http.get(url, { params })
}

/**
 * 详情模板基本信息列表
 * @returns
 */
export const detailTplBaseInfoListApi = async () => {
  const url = `${API_VERSION}admin/detail-tpl-base-info-list`
  return http.get(url)
}

export const tplListByCidApi = async (params) => {
  const url = `${API_VERSION}admin/detail-tpl-list-by-cid`
  return http.get(url, { params })
}

/**
 * [addTpl 添加详情模板]
 * @param {[type]} params [description]
 */
export const addTplApi = (data) => {
  const url = `${API_VERSION}admin/add-detail-tpl`
  return http.post(url, data)
}

/**
 * [editTpl 编辑详情模板]
 * @param {[type]} params [description]
 */
export const editTplApi = (data) => {
  const url = `${API_VERSION}admin/edit-detail-tpl`
  return http.post(url, data)
}

/**
 * 详情模板详情
 * @param params
 * @returns
 */
export const tplDetailApi = async (params) => {
  const url = `${API_VERSION}admin/detail-tpl-detail`
  return http.get(url, { params })
}

/**
 * 删除详情模板
 * @param params
 * @returns
 */
export const deleteTplApi = async (params) => {
  const url = `${API_VERSION}admin/delete-detail-tpl`
  return http.get(url, { params })
}
