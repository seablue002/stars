import http from '@/utils/http'
import { API_VERSION } from '@/config/http'

/**
 * 获取配置选项列表
 * @returns
 */
export interface SystemConfigListProps {
  label: string;
  cid: string;
  page: number;
  size: number;
}
export const systemConfigListApi = async (params: SystemConfigListProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}system-config-list`
  return http.get(url, { params })
}

export interface SystemConfigListByCidProps {
  cid: string|number;
}
export const systemConfigListByCidApi = async (params: SystemConfigListByCidProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}system-config-list-by-cid`
  return http.get(url, { params })
}

/**
 * [addSystemConfig 添加配置选项]
 * @param {[type]} params [description]
 */
export interface SystemConfigProps {
  id?: string;
  cid: string;
  field: string;
  label: string;
  tips: string;
  type: string;
  props?: string;
  options?: string;
  value?: string;
  'validate_rules'?: string;
  status: number;
  sort: number;
}
export const addSystemConfigApi = (data: SystemConfigProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}add-system-config`
  return http.post(url, data)
}

/**
 * [editSystemConfig 编辑配置选项]
 * @param {[type]} params [description]
 */
export interface EditSystemConfigProps extends SystemConfigProps {
  id: string;
}
export const editSystemConfigApi = (data: EditSystemConfigProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}edit-system-config`
  return http.post(url, data)
}

/**
 * 配置选项详情
 * @param params
 * @returns
 */
export interface SystemConfigDetailProps {
  id: string;
}
export const systemConfigDetailApi = async (params: SystemConfigDetailProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}system-config-detail`
  return http.get(url, { params })
}

/**
 * 删除配置选项
 * @param params
 * @returns
 */
export interface DeleteSystemConfigProps {
  id: string;
}
export const deleteSystemConfigApi = async (params: DeleteSystemConfigProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}delete-system-config`
  return http.get(url, { params })
}

export interface SettingSaveSystemConfigProps {
  [key: string]: any;
}
export const settingSaveSystemConfigApi = (data: SettingSaveSystemConfigProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}setting-save-system-config`
  return http.post(url, data)
}
