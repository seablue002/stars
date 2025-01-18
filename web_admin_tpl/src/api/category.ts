import http from '@/utils/http'
import { API_VERSION } from '@/config/http'

/**
 * 获取分类列表
 * @returns
 */
export const categoryListApi = async (): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}category-list`
  return http.get(url)
}

/**
 * [addCategory 添加商品分类]
 * @param {[type]} params [description]
 */
export interface CategoryProps {
  id?: string;
  name: string;
  pid: string;
  'category_logo': string;
  sort: string;
}
export const addCategoryApi = (data: CategoryProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}add-category`
  return http.post(url, data)
}

/**
 * [editCategory 编辑商品分类]
 * @param {[type]} params [description]
 */
export interface EditCategoryProps extends CategoryProps {
  id: string;
}
export const editCategoryApi = (data: EditCategoryProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}edit-category`
  return http.post(url, data)
}

/**
 * 商品分类详情
 * @param params
 * @returns
 */
export interface CategoryDetailProps {
  id: string;
}
export const categoryDetailApi = async (params: CategoryDetailProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}category-detail`
  return http.get(url, { params })
}

/**
 * 删除商品分类
 * @param params
 * @returns
 */
export interface DeleteCategoryProps {
  id: string;
}
export const DeleteCategoryApi = async (params: DeleteCategoryProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}delete-category`
  return http.get(url, { params })
}
