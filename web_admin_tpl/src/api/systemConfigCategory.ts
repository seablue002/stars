import http from '@/utils/http'
import { API_VERSION } from '@/config/http'

/**
 * 获取分类列表
 * @returns
 */
export const systemConfigCategoryListApi = async (): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}system-config-category-list`
  return http.get(url)
}

/**
 * 获取分类列表by rid
 * @returns
 */
export interface SystemConfigCategoryByRidProps {
  rid: number;
}
export const systemConfigCategoryListByRidApi = async (params: SystemConfigCategoryByRidProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}system-config-category-list-by-rid`
  return http.get(url, { params })
}

/**
 * [addSystemConfigCategory 添加系统配置分类]
 * @param {[type]} params [description]
 */
export interface SystemConfigCategoryProps {
  id?: string;
  name: string;
  pid: any;
  sort: string;
}
export const addSystemConfigCategoryApi = (data: SystemConfigCategoryProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}add-system-config-category`
  return http.post(url, data)
}

/**
 * [editSystemConfigCategory 编辑系统配置分类]
 * @param {[type]} params [description]
 */
export interface EditSystemConfigCategoryProps extends SystemConfigCategoryProps {
  id: string;
}
export const editSystemConfigCategoryApi = (data: EditSystemConfigCategoryProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}edit-system-config-category`
  return http.post(url, data)
}

/**
 * 系统配置分类详情
 * @param params
 * @returns
 */
export interface SystemConfigCategoryDetailProps {
  id: string;
}
export const systemConfigCategoryDetailApi = async (params: SystemConfigCategoryDetailProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}system-config-category-detail`
  return http.get(url, { params })
}

/**
 * 删除系统配置分类
 * @param params
 * @returns
 */
export interface DeleteSystemConfigCategoryProps {
  id: string;
}
export const deleteSystemConfigCategoryApi = async (params: DeleteSystemConfigCategoryProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}delete-system-config-category`
  return http.get(url, { params })
}
