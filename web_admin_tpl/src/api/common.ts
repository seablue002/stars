import http from '@/utils/http'
import { API_VERSION } from '@/config/http'

/**
 * 根据字典类型key获取字典列表
 * @param params
 * @returns
 */
export interface DicListProps {
  'dic_type_keys': string[];
}
export const dicListApi = async (params: DicListProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}dic-list`
  return http.get(url, { params })
}

/**
 * 根据pid获取区域信息列表
 * @param params
 * @returns
 */
export interface RegionListProps {
  pid: number;
}
export const regionListApi = async (params: RegionListProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}region-list`
  return http.get(url, { params })
}

export interface SystemConfigProps {
  'config_keys': string[];
}
export const systemConfigApi = async (params: SystemConfigProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}system-config`
  return http.get(url, { params })
}

export const systemInfoApi = async (): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}system-info`
  return http.get(url)
}
