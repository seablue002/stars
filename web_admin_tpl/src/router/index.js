/*
 * 路由router主文件
 */
import { createRouter, createWebHistory } from 'vue-router'
import routes from './routes'

// 实例化路由
const router = createRouter({
  history: createWebHistory(),
  routes,
})
export default router

// 注册路由
export function registRouter(app) {
  app.use(router)
}
