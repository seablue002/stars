import http from '@/utils/http'
import { API_VERSION } from '@/config/http'

/**
 * 获取信息列表
 * @returns
 */
export interface InfoListProps {
  title: string;
  'column_id': string;
  page: number;
  size: number;
}
export const infoListApi = async (params: InfoListProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}info-list`
  return http.get(url, { params })
}

export interface InfoListByCidProps {
  pid: string|number;
}
export const infoListByCidApi = async (params: InfoListByCidProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}info-list-by-pid`
  return http.get(url, { params })
}

/**
 * [addInfo 添加信息]
 * @param {[type]} params [description]
 */
export interface InfoProps {
  id?: string;
  'column_id': string;
  title: string;
  'sub_title': string;
  content: string;
  'title_url': string;
  cover: string;
  label: string[];
  'publish_time': string;
}
export const addInfoApi = (data: InfoProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}add-info`
  return http.post(url, data)
}

/**
 * [editInfo 编辑信息]
 * @param {[type]} params [description]
 */
export interface EditInfoProps extends InfoProps {
  id: string;
}
export const editInfoApi = (data: EditInfoProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}edit-info`
  return http.post(url, data)
}

/**
 * 信息详情
 * @param params
 * @returns
 */
export interface InfoDetailProps {
  id: string;
}
export const infoDetailApi = async (params: InfoDetailProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}info-detail`
  return http.get(url, { params })
}

/**
 * 删除信息
 * @param params
 * @returns
 */
export interface DeleteInfoProps {
  id: string;
}
export const deleteInfoApi = async (params: DeleteInfoProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}delete-info`
  return http.get(url, { params })
}

/**
 * 删除封面
 * @param params
 * @returns
 */
export interface DeleteCoverProps {
  'info_id': string;
}
export const deleteCoverApi = async (params: DeleteCoverProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}delete-cover`
  return http.get(url, { params })
}
