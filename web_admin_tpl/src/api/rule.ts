import http from '@/utils/http'
import { API_VERSION } from '@/config/http'

/**
 * 权限规则基本信息列表
 * @returns
 */
export const ruleBaseInfoListApi = async (): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}auth-rule-base-info-list`
  return http.get(url)
}

/**
 * 权限规格列表
 * @param params
 * @returns
 */
export interface RuleListProps {
  name: string;
  page: number;
  size: number;
}
export const ruleListApi = async (params: RuleListProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}auth-rule-list`
  return http.get(url, { params })
}

/**
 * 添加权限规则
 * @param params
 * @returns
 */
export interface RuleProps {
  id?: string;
  name: string;
  title: string;
  pid: string;
  'menu_type': number;
  status: number;
}
export const addRuleApi = async (params: RuleProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}add-auth-rule`
  return http.post(url, params)
}

/**
 * 编辑权限规则
 * @param params
 * @returns
 */
export interface EditRuleProps extends RuleProps {
  id: string
}
export const editRuleApi = async (params: EditRuleProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}edit-auth-rule`
  return http.post(url, params)
}

/**
 * 权限规则详情
 * @param params
 * @returns
 */
export interface RuleDetailProps {
  id: string
}
export const ruleDetailApi = async (params: RuleDetailProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}auth-rule-detail`
  return http.get(url, { params })
}
