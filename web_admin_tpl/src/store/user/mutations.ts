// import { matchPermissionRoutes } from '@/router/routes'
import { UserProps, AdminUserInfoProps } from './index'
export default {
  setAdminUserInfo (state: UserProps, val: AdminUserInfoProps): void {
    // 添加根据用户权限动态生成的页面菜单路由
    // matchPermissionRoutes(val.menuList)

    state.adminUserInfo = val
    // 持久化存储登录信息
    localStorage.setItem('adminUserInfo', JSON.stringify(state.adminUserInfo))
  },
  initAdminUserInfoVal (state: UserProps): void {
    const info = localStorage.getItem('adminUserInfo')
    if (info) {
      state.adminUserInfo = JSON.parse(info)
      // matchPermissionRoutes(state.adminUserInfo.menuList)
    }
  },
  clearAdminUserInfo (state: UserProps): void {
    // state.adminUserInfo = { token: '', menuList: [], buttonList: [] }
    state.adminUserInfo = { token: '', admin_info: {} }
    localStorage.removeItem('adminUserInfo')
  }
}
