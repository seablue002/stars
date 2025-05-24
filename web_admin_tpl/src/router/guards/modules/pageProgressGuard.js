/*
 * 页面访问守卫
 */
import useAppStore from '@/store/modules/app'
import NProgress from 'nprogress'
import 'nprogress/nprogress.css'
import { NOT_ADD_TO_VISITED_TAGS_ROUTES_PATH } from '@/settings/config/router'

export default function pageProgressGuard(router) {
  router.beforeEach((to, from, next) => {
    // 页面加载loading进度条显示
    NProgress.start()
    next()
  })

  router.afterEach((to) => {
    // 记录访问历史路由，用于历史访问tags功能
    if (!NOT_ADD_TO_VISITED_TAGS_ROUTES_PATH.includes(to.path)) {
      const appStore = useAppStore()
      appStore.addOneVisitedTags(to)
    }

    // 页面加载loading进度条隐藏
    NProgress.done()
  })
}
