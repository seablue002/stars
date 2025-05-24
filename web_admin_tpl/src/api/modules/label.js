import { http } from '@/utils/http'
import { API_VERSION } from '@/settings/config/http'

// 获取标签列表
export const labelListApi = async () => {
  const url = `${API_VERSION}admin/label-list`
  return http.get(url)
}

// 标签基本信息列表
export const labelBaseInfoListApi = async () => {
  const url = `${API_VERSION}admin/label-base-info-list`
  return http.get(url)
}

// 添加标签
export const addLabelApi = (data) => {
  const url = `${API_VERSION}admin/add-label`
  return http.post(url, data)
}

// 编辑标签
export const editLabelApi = (data) => {
  const url = `${API_VERSION}admin/edit-label`
  return http.post(url, data)
}

// 标签详情
export const labelDetailApi = async (params) => {
  const url = `${API_VERSION}admin/label-detail`
  return http.get(url, { params })
}

// 删除标签
export const deleteLabelApi = async (data) => {
  const url = `${API_VERSION}admin/delete-label`
  return http.post(url, data)
}
