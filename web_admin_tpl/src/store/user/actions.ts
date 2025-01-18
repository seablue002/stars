import { ActionContext } from 'vuex'
import { UserProps } from './index'

import { HTTP_CONFIG } from '@/config/http'
import messageBox from '@/utils/message'
import { ElMessageBox } from 'element-plus'
import router from '@/router'
import store from '@/store'

import {
  LoginProps,
  loginApi,
  logoutApi
} from '@/api/adminUser'

export default {
  async loginAct ({ commit }: ActionContext<UserProps, any>, loginData: LoginProps): Promise<ReqRes.ResponseResult> {
    let loginApiRes: ReqRes.ResponseResult = { status: 0, data: '', message: '' }
    loginApiRes = await loginApi(loginData)
    const { status, data, message } = loginApiRes
    if (status === HTTP_CONFIG.API_SUCCESS_CODE) {
      if (data.token) {
        commit('setAdminUserInfo', data)
        // 进入用户中心首页
        router.replace({ path: '/' })
      }
      return Promise.resolve({ status, data, message })
    } else {
      return Promise.resolve({ status, data, message })
    }
  },
  async out (): Promise<void> {
    ElMessageBox.confirm(
      '确定退出登录吗?',
      '提示',
      {
        confirmButtonText: '立即退出',
        cancelButtonText: '取消',
        type: 'warning'
      }
    ).then(async () => {
      const { status, message } = await logoutApi()
      if (status === HTTP_CONFIG.API_SUCCESS_CODE) {
        // 清除用户登录态数据
        store.commit('user/clearAdminUserInfo')

        // 清空访问历史记录tags集合
        store.commit('app/clearRouterHistoryTags')
        messageBox.success({
          message,
          duration: 3000
        })

        router.replace({ path: '/login' })
      } else {
        messageBox.warning({
          message,
          duration: 3000
        })
      }
    }).catch(() => {
      return '取消登出'
    })
  }
}
