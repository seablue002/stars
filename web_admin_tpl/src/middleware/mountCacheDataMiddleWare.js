/*
 * 数据持久化，装载本地存储数据到store
 */
import useUserStore from '@/store/modules/user'

const mountCacheDataMiddleWare = async (app) => {
  const { $cache } = app.config.globalProperties
  const userStore = useUserStore()

  // 装载持久化数据到store
  const mountCacheDataToStore = () => {
    const cacheStorage = $cache.storage
    // token装载
    const tokenCache = cacheStorage.localStorage.getCache('token')
    userStore.updateToken(tokenCache)

    // 用户信息装载
    const userInfoCache = cacheStorage.localStorage.getCache('user')
    userStore.updateUserInfo(userInfoCache)

    // 用户权限装载
    const authListCache = cacheStorage.localStorage.getCache('auth')
    userStore.updateAuthList(authListCache)
  }
  mountCacheDataToStore()
}

export default mountCacheDataMiddleWare
