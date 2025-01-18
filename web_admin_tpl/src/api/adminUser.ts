import http from '@/utils/http'
import { API_VERSION } from '@/config/http'

/**
 * [loginApi 系统登录]
 * @param  {LoginProps} data [description]
 * @return {Promise<ReqRes.ResponseResult>}        [description]
 */
export interface LoginProps {
  username: string;
  password: string;
  captcha: string;
  'captcha_key'?: string;
}
export const loginApi = async (data: LoginProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}login-in`
  return http.post(url, data)
}

/**
 * [getCaptcha 获取验证码base64]
 * @return {[type]} [description]
 */
export const getCaptchaApi = async (): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}verify-img`
  return http.get(url)
}

/**
 * 获取管理员列表
 * @param params
 * @returns
 */
export interface AdminUserListProps {
  name: string;
  page: number;
  size: number;
}
export const adminUserListApi = async (params: AdminUserListProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}admin-user-list`
  return http.get(url, { params })
}

/**
 * 添加管理员
 * @param params
 * @returns
 */
export interface AdminUserProps {
  id?: string;
  username: string;
  password?: string;
  status: number;
  role: string[];
  [key: string]: any;
}
export const addAdminUserApi = async (params: AdminUserProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}add-admin-user`
  return http.post(url, params)
}

/**
 * 编辑管理员
 * @param params
 * @returns
 */
export interface EditAdminUserProps {
  id: string;
  username: string;
  password?: string;
  status: number;
  role: string[];
  [key: string]: any;
}
export const editAdminUserApi = async (params: EditAdminUserProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}edit-admin-user`
  return http.post(url, params)
}

/**
 * 管理员详情
 * @param params
 * @returns
 */
export interface AdminUserDetailProps {
  id: string;
}
export const adminUserDetailApi = async (params: AdminUserDetailProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}admin-user-detail`
  return http.get(url, { params })
}

/**
 * 退出登录
 * @returns
 */
export const logoutApi = async (): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}login-out`
  return http.post(url)
}
