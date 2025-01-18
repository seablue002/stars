import { Module } from 'vuex'
import { GlobalDataProps } from '../index'
import state from './states'
import getters from './getters'
import mutations from './mutations'
import actions from './actions'

export interface AdminUserInfoProps {
  token: string;
  'admin_info': any
}
export interface UserProps {
  adminUserInfo: AdminUserInfoProps;
}

const user: Module<UserProps, GlobalDataProps> = {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
export default user
