/*
 * 启用路由相关的各类守卫
 */
import createPageProgressGuard from './modules/pageProgressGuard'
import createPageLeaveGuard from './modules/pageLeaveGuard'
import createTokenGuard from './modules/tokenGuard'

export default function registRouterGuards(router) {
  createPageLeaveGuard(router)
  createPageProgressGuard(router)
  createTokenGuard(router)
}
