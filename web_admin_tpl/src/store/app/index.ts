import { Module } from 'vuex'
import { GlobalDataProps } from '../index'
import state from './state'
import mutations from './mutations'
import { RouteLocationNormalized } from 'vue-router'

export interface AppProps {
  sidebarIsClose: boolean;
  sidebarW: string;
  routerHistoryTags: RouteLocationNormalized[];
}

const app: Module<AppProps, GlobalDataProps> = {
  namespaced: true,
  state,
  mutations
}

export default app
