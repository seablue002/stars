import http from '@/utils/http'
import { API_VERSION } from '@/config/http'

export const roleBaseInfoListApi = async (): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}auth-role-base-info-list`
  return http.get(url)
}

/**
 * 获取角色列表
 * @param params
 * @returns
 */
export interface RoleListProps {
  page: number;
  size: number;
}
export const roleListApi = async (params: RoleListProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}auth-role-list`
  return http.get(url, { params })
}

/**
 * 添加角色
 * @param params
 * @returns
 */
export interface RoleProps {
  id?: string;
  title: string;
  status: number;
  rule: string[];
}
export const addRoleApi = async (params: RoleProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}add-auth-role`
  return http.post(url, params)
}

/**
 * 编辑角色
 * @param params
 * @returns
 */
export interface EditRoleProps extends RoleProps {
  id: string
}
export const editRoleApi = async (params: EditRoleProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}edit-auth-role`
  return http.post(url, params)
}

/**
 * 角色详情
 * @param params
 * @returns
 */
export interface RoleDetailProps {
  id: string
}
export const roleDetailApi = async (params: RoleDetailProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}auth-role-detail`
  return http.get(url, { params })
}
