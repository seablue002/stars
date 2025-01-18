import http from '@/utils/http'
import { API_VERSION } from '@/config/http'

/**
 * 获取轮播图列表
 * @param params
 * @returns
 */
export interface SliderListProps {
  name?: string;
  page: number;
  size: number;
}
export const sliderListApi = async (params?: SliderListProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}slider-list`
  return http.get(url, { params })
}

/**
 * [addSlider 添加轮播图]
 * @param {[type]} params [description]
 */
export interface SliderProps {
  id?: string;
  name: string;
  slider: File | string;
  'page_url': string;
  sort: number | null;
  status: number;
  remarks: string;
  [key: string]: any;
}
export const addSliderApi = (data: SliderProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}add-slider`
  return http.post(url, data)
}

/**
 * 轮播图详情
 * @param params
 * @returns
 */
export interface SliderDetailProps {
  id: string;
}
export const sliderDetailApi = (params: SliderDetailProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}slider-detail`
  return http.get(url, { params })
}

/**
 * 编辑轮播图
 * @param data
 * @returns
 */
export interface EditSliderProps extends SliderProps {
  id: string;
}
export const editSliderApi = (data: EditSliderProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}edit-slider`
  return http.post(url, data)
}

/**
 * 删除轮播图
 * @param params
 * @returns
 */
export interface DelSliderProps {
  id: number;
}
export const delSliderApi = (params: DelSliderProps): Promise<ReqRes.ResponseResult> => {
  const url = `${API_VERSION}delete-slider`
  return http.get(url, { params })
}
