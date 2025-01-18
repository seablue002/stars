import http from '@/utils/http'
import { API_VERSION } from '@/config/http'

/**
 * 获取列表模板列表
 * @returns
 */
export interface TplListProps {
  name: string;
  'category_id'?: string;
  page: number;
  size: number;
}
export const tplListApi = async (params: TplListProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}list-tpl-list`
  return http.get(url, { params })
}

/**
 * 列表模板基本信息列表
 * @returns
 */
export const listTplBaseInfoListApi = async (): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}list-tpl-base-info-list`
  return http.get(url)
}

export interface TplListByCidProps {
  'category_id': string|number;
}
export const tplListByCidApi = async (params: TplListByCidProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}list-tpl-list-by-cid`
  return http.get(url, { params })
}

/**
 * [addTpl 添加列表模板]
 * @param {[type]} params [description]
 */
export interface TplProps {
  id?: string;
  'category_id': string;
  name: string;
  content: string;
  'item_var': string;
}
export const addTplApi = (data: TplProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}add-list-tpl`
  return http.post(url, data)
}

/**
 * [editTpl 编辑列表模板]
 * @param {[type]} params [description]
 */
export interface EditTplProps extends TplProps {
  id: string;
}
export const editTplApi = (data: EditTplProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}edit-list-tpl`
  return http.post(url, data)
}

/**
 * 列表模板详情
 * @param params
 * @returns
 */
export interface TplDetailProps {
  id: string;
}
export const tplDetailApi = async (params: TplDetailProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}list-tpl-detail`
  return http.get(url, { params })
}

/**
 * 删除列表模板
 * @param params
 * @returns
 */
export interface DeleteTplProps {
  id: number;
}
export const deleteTplApi = async (params: DeleteTplProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}delete-list-tpl`
  return http.get(url, { params })
}
