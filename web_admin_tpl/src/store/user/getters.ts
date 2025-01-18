import { UserProps, AdminUserInfoProps } from './index'
export default {
  getAdminUserInfo: (state: UserProps): AdminUserInfoProps => {
    return state.adminUserInfo
  },
  getToken: (state: UserProps): string => {
    return state.adminUserInfo.token
  }
}
