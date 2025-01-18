import { createRouter, createWebHashHistory } from 'vue-router'
import store from '@/store'
import { baseRoutes } from './routes'

// import { APP_NAME } from '@/config/app'
import ROUTER_WHITE_LIST from '@/config/routerWhiteList'

const router = createRouter({
  history: createWebHashHistory(),
  routes: baseRoutes
})
router.beforeEach((to, from, next) => {
  /* 动态设置网页title */
  // let webTitle = <HTMLTitleElement | null>document.querySelector('title')
  // webTitle.innerText = to.meta.title || APP_NAME

  // 是否登录状态检测
  const token = store.state.user.adminUserInfo.token
  if (ROUTER_WHITE_LIST.includes(to.path)) {
    next()
  } else {
    if (!token) {
      next({ path: '/login' })
    } else {
      if (/\/edit$/.test(to.path) || /\/detail$/.test(to.path)) {
        if (to.query.id) {
          next()
        } else {
          next(to.path.replace(/\/(edit|detail)/i, '/list'))
        }
      } else {
        next()
      }
    }
  }
})

router.afterEach((to) => {
  if (!ROUTER_WHITE_LIST.includes(to.path)) {
    store.commit('app/addRouterHistoryTagsVal', to)
  }
})
export default router
