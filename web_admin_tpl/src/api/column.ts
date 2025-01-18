import http from '@/utils/http'
import { API_VERSION } from '@/config/http'

/**
 * 获取栏目列表
 * @returns
 */
export interface ColumnListProps {
  name: string;
  pid: string;
  page: number;
  size: number;
}
export const columnListApi = async (): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}column-list`
  return http.get(url)
}

/**
 * [addColumn 添加栏目]
 * @param {[type]} params [description]
 */
export interface ColumnProps {
  id?: string;
  name: string;
  pid: string;
  'model_tb_name': string;
  sort: string;
  [key: string]: any;
}
export const addColumnApi = (data: ColumnProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}add-column`
  return http.post(url, data)
}

/**
 * [editColumn 编辑栏目]
 * @param {[type]} params [description]
 */
export interface EditColumnProps extends ColumnProps {
  id: string;
}
export const editColumnApi = (data: EditColumnProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}edit-column`
  return http.post(url, data)
}

/**
 * 栏目详情
 * @param params
 * @returns
 */
export interface ColumnDetailProps {
  id: string;
}
export const columnDetailApi = async (params: ColumnDetailProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}column-detail`
  return http.get(url, { params })
}

/**
 * 删除栏目
 * @param params
 * @returns
 */
export interface DeleteColumnProps {
  id: string;
}
export const deleteColumnApi = async (params: DeleteColumnProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}delete-column`
  return http.get(url, { params })
}

/**
 * 删除栏目封面
 * @param params
 * @returns
 */
export interface DeleteColumnCoverProps {
  'column_id': string;
}
export const deleteColumnCoverApi = async (params: DeleteColumnCoverProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}delete-column-cover`
  return http.get(url, { params })
}
