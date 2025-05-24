/*
 * 路由配置
 */
// 默认首页路由地址
export const DEFAULT_HOME_PAGE_ROUTE_PATH = '/home'

// 404
export const NOT_FOUND_PAGE_ROUTE_PATH = '/404'

// 无访问权限
export const NO_AUTH_ROUTE_PATH = '/noAuth'

// 登录页面路由地址
export const LOGIN_PAGE_ROUTE_PATH = '/login'

// 路由白名单
export const WHITE_ROUTES_PATH = [
  '/',
  LOGIN_PAGE_ROUTE_PATH,
  NO_AUTH_ROUTE_PATH,
]

// 不进行生成tag历史记录的路由
export const NOT_ADD_TO_VISITED_TAGS_ROUTES_PATH = [
  '/',
  LOGIN_PAGE_ROUTE_PATH,
  NOT_FOUND_PAGE_ROUTE_PATH,
  NO_AUTH_ROUTE_PATH,
]

// 需要进行离开确认的页面路由path地址
export const NEED_CONFIRM_PAGE_ROUTE_PATH_LIST = [
  // 框架使用-》离开页面确认
  '/demo/framework/pageLeaveConfirm',
]
