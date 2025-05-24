/*
 * app相关配置
 */
// 应用版本号
export const APP_VERSION = `v${import.meta.env.VITE_APP_VERSION}`

export const APP_TITLE = import.meta.env.VITE_APP_TITLE

// 分页尺寸
export const PAGE_SIZES = [5, 10, 20, 30, 40, 50]

export default {
  PAGE_SIZES,
}
