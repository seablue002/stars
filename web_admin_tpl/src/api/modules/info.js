import { http } from '@/utils/http'
import { API_VERSION } from '@/settings/config/http'

// 获取信息列表
export const infoListApi = async (params) => {
  const url = `${API_VERSION}/admin/info-list`
  return http.get(url, { params })
}

// 添加信息
export const addInfoApi = (data) => {
  const url = `${API_VERSION}/admin/add-info`
  return http.post(url, data)
}

// 编辑信息
export const editInfoApi = (data) => {
  const url = `${API_VERSION}/admin/edit-info`
  return http.post(url, data)
}

// 信息详情
export const infoDetailApi = async (params) => {
  const url = `${API_VERSION}/admin/info-detail`
  return http.get(url, { params })
}

// 删除信息
export const deleteInfoApi = async (data) => {
  const url = `${API_VERSION}/admin/delete-info`
  return http.post(url, data)
}

// 删除信息缓存
export const deleteInfoCacheApi = async (data) => {
  const url = `${API_VERSION}/admin/delete-info-cache`
  return http.post(url, data)
}

// 删除封面
export const deleteCoverApi = async (params) => {
  const url = `${API_VERSION}/admin/delete-cover`
  return http.get(url, { params })
}

export const deleteResourceApi = async (data) => {
  const url = `${API_VERSION}/admin/delete-resource`
  return http.post(url, data)
}
