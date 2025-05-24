/*
 * 初始应用时生成路由权限菜单
 */
import useUserStore from '@/store/modules/user'
import { formatAndAddPermissionRoutes } from '@/utils/other/common'

const addPermissionRoutesMiddleWare = async () => {
  const userStore = useUserStore()
  formatAndAddPermissionRoutes(
    userStore.authList,
    userStore.updateAblePermissionRoutesFullTree,
    userStore.updateAblePermissionButtons
  )
}

export default addPermissionRoutesMiddleWare
