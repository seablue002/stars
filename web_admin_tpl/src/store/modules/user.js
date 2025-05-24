/*
 * 用户相关状态
 */
import { ref } from 'vue'
import { defineStore } from 'pinia'
import { formatAndAddPermissionRoutes } from '@/utils/other/common'
import $cache from '@/utils/cache'
import $api from '@/api'
import { API_CODE as $apiCode } from '@/settings/config/http'

const useUserStore = defineStore('useUserStore', () => {
  const cacheStorage = $cache.storage

  // 用户登录数据
  const token = ref(null)
  const updateToken = (tokenVal) => {
    token.value = tokenVal
  }

  const userInfo = ref(null)
  const updateUserInfo = (user) => {
    userInfo.value = user
  }

  // 登录
  const login = (params) => {
    return new Promise((resolve, reject) => {
      $api.login
        .loginApi(params)
        .then((loginRes) => {
          const { status, data, message } = loginRes.data
          if (status === $apiCode.SUCCESS) {
            // store存储token、userInfo
            token.value = data.token
            userInfo.value = data.admin_info
            // authList.value = data.auth

            // 进行持久化存储storage
            cacheStorage.localStorage.setCache('token', token.value)
            cacheStorage.localStorage.setCache('user', userInfo.value)
            // 持久化存储权限数据
            // cacheStorage.localStorage.setCache('auth', authList.value)

            formatAndAddPermissionRoutes(
              authList.value,
              updateAblePermissionRoutesFullTree,
              updateAblePermissionButtons
            )
            resolve(loginRes)
          } else {
            reject(message)
          }
        })
        .catch(() => {
          reject(new Error('登录失败'))
        })
    })
  }

  // 用户总权限列表（菜单+按钮）
  const authList = ref([])
  const updateAuthList = (auth) => {
    authList.value = auth
  }

  // 单独通过用户登录token获取用户权限
  const getAuthList = () => {
    return new Promise((resolve, reject) => {
      $api.user
        .getAuthList()
        .then((authRes) => {
          const { code, data } = authRes.data
          if (code === $apiCode.SUCCESS) {
            authList.value = data

            // 持久化存储权限数据
            cacheStorage.localStorage.setCache('auth', authList.value)
            resolve()
          } else {
            reject()
          }
        })
        .catch(() => {
          reject()
        })
    })
  }

  // 接口返回用户可访问权限authList, 与本地路由匹配所得最终可访问权限菜单完整树tree
  const ablePermissionRoutesFullTree = ref(null)
  const updateAblePermissionRoutesFullTree = (permissionRoutes) => {
    ablePermissionRoutesFullTree.value = permissionRoutes
  }

  // 用户按钮权限列表（目前没有使用type = button按钮筛选），直接保存了所有菜单和按钮类型的code标识码
  const ablePermissionButtons = ref([])
  const updateAblePermissionButtons = (permissionButtons) => {
    ablePermissionButtons.value = permissionButtons
  }

  // 退出登录系统
  const loginOut = () => {
    return new Promise((resolve, reject) => {
      $api.login
        .logoutApi()
        .then((apiRes) => {
          const { status, message } = apiRes.data
          if (status === $apiCode.SUCCESS) {
            clear()
            resolve(message)
          } else {
            reject(new Error(message))
          }
        })
        .catch(() => {
          reject()
        })
    })
  }

  // 清空user相关store数据
  const clear = () => {
    token.value = null
    userInfo.value = null
    authList.value = []
    ablePermissionRoutesFullTree.value = []

    // 清除用户storage持久化数据
    cacheStorage.localStorage.removeMultipleCache(['token', 'user', 'auth'])
  }

  return {
    token,
    updateToken,
    userInfo,
    updateUserInfo,
    login,
    authList,
    updateAuthList,
    getAuthList,
    ablePermissionRoutesFullTree,
    updateAblePermissionRoutesFullTree,
    ablePermissionButtons,
    updateAblePermissionButtons,
    loginOut,
    clear,
  }
})

export default useUserStore
