import { http } from '@/utils/http'
import { API_VERSION } from '@/settings/config/http'

// 获取栏目列表
export const columnListApi = async () => {
  const url = `${API_VERSION}admin/column-list`
  return http.get(url)
}

// 添加栏目
export const addColumnApi = (data) => {
  const url = `${API_VERSION}admin/add-column`
  return http.post(url, data)
}

// 编辑栏目
export const editColumnApi = (data) => {
  const url = `${API_VERSION}admin/edit-column`
  return http.post(url, data)
}

// 栏目详情
export const columnDetailApi = async (params) => {
  const url = `${API_VERSION}admin/column-detail`
  return http.get(url, { params })
}

// 删除栏目
export const deleteColumnApi = async (params) => {
  const url = `${API_VERSION}admin/delete-column`
  return http.get(url, { params })
}

// 删除栏目封面
export const deleteColumnCoverApi = async (params) => {
  const url = `${API_VERSION}admin/delete-column-cover`
  return http.get(url, { params })
}
