import http from '@/utils/http'
import { API_VERSION } from '@/config/http'

/**
 * 获取栏目自定义字段配置选项列表
 * @returns
 */
export interface ColumnExtendFieldsConfigListProps {
  label: string;
  page: number;
  size: number;
}
export const columnExtendFieldsConfigListApi = async (params: ColumnExtendFieldsConfigListProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}column-extend-fields-config-list`
  return http.get(url, { params })
}

/**
 * 获取栏目自定义字段配置选项全部列表
 * @returns
 */
export const columnExtendFieldsConfigAllListApi = async (): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}column-extend-fields-config-all-list`
  return http.get(url)
}

/**
 * [addColumnExtendFieldsConfig 添加栏目自定义字段配置选项]
 * @param {[type]} params [description]
 */
export interface ColumnExtendFieldsConfigProps {
  id?: string;
  field: string;
  'field_type': string;
  'field_length': string;
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
export const addColumnExtendFieldsConfigApi = (data: ColumnExtendFieldsConfigProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}add-column-extend-fields-config`
  return http.post(url, data)
}

/**
 * [editColumnExtendFieldsConfig 编辑栏目自定义字段配置选项]
 * @param {[type]} params [description]
 */
export interface EditColumnExtendFieldsConfigProps extends ColumnExtendFieldsConfigProps {
  id: string;
}
export const editColumnExtendFieldsConfigApi = (data: EditColumnExtendFieldsConfigProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}edit-column-extend-fields-config`
  return http.post(url, data)
}

/**
 * 栏目自定义字段配置选项详情
 * @param params
 * @returns
 */
export interface ColumnExtendFieldsConfigDetailProps {
  id: string;
}
export const columnExtendFieldsConfigDetailApi = async (params: ColumnExtendFieldsConfigDetailProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}column-extend-fields-config-detail`
  return http.get(url, { params })
}

/**
 * 删除栏目自定义字段配置选项
 * @param params
 * @returns
 */
export interface DeleteColumnExtendFieldsConfigProps {
  id: string;
}
export const deleteColumnExtendFieldsConfigApi = async (params: DeleteColumnExtendFieldsConfigProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}delete-column-extend-fields-config`
  return http.get(url, { params })
}

export interface SettingSaveColumnExtendFieldsConfigProps {
  [key: string]: any;
}
export const settingSaveColumnExtendFieldsConfigApi = (data: SettingSaveColumnExtendFieldsConfigProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}setting-save-column-extend-fields-config`
  return http.post(url, data)
}
