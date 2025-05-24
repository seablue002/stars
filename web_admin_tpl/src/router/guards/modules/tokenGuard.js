import useUserStore from '@/store/modules/user'
import {
  WHITE_ROUTES_PATH,
  LOGIN_PAGE_ROUTE_PATH,
} from '@/settings/config/router'

export default function tokenGuard(router) {
  router.beforeEach((to, from, next) => {
    const userStore = useUserStore()
    const { token } = userStore
    if (!token && !WHITE_ROUTES_PATH.includes(to.path)) {
      next(LOGIN_PAGE_ROUTE_PATH)
      return
    }
    next()
  })
}
