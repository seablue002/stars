import { Module } from 'vuex'
import { GlobalDataProps } from '../index'
import state from './states'
import getters from './getters'
import mutations from './mutations'
import actions from './actions'

export interface CommonProps {
  config: {
    systemConfig: {[key: string]: any},
    [key: string]: any;
  };
}

const common: Module<CommonProps, GlobalDataProps> = {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
export default common
