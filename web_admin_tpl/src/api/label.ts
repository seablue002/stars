import http from '@/utils/http'
import { API_VERSION } from '@/config/http'

/**
 * 获取标签列表
 * @returns
 */
export interface LabelListProps {
  name: string;
  pid: string;
  page: number;
  size: number;
}
export const labelListApi = async (): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}label-list`
  return http.get(url)
}

/**
 * 标签基本信息列表
 * @returns
 */
export const labelBaseInfoListApi = async (): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}label-base-info-list`
  return http.get(url)
}

/**
 * [addLabel 添加标签]
 * @param {[type]} params [description]
 */
export interface LabelProps {
  id?: string;
  name: string;
  pid: string;
  sort: string;
  [key: string]: any;
}
export const addLabelApi = (data: LabelProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}add-label`
  return http.post(url, data)
}

/**
 * [editLabel 编辑标签]
 * @param {[type]} params [description]
 */
export interface EditLabelProps extends LabelProps {
  id: string;
}
export const editLabelApi = (data: EditLabelProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}edit-label`
  return http.post(url, data)
}

/**
 * 标签详情
 * @param params
 * @returns
 */
export interface LabelDetailProps {
  id: string;
}
export const labelDetailApi = async (params: LabelDetailProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}label-detail`
  return http.get(url, { params })
}

/**
 * 删除标签
 * @param params
 * @returns
 */
export interface DeleteLabelProps {
  id: string;
}
export const deleteLabelApi = async (params: DeleteLabelProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}delete-label`
  return http.get(url, { params })
}
