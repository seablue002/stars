/*
 * 状态管理主文件
 */
import { createPinia } from 'pinia'

const store = createPinia()
export default store

export function registStore(app) {
  app.use(store)
}
