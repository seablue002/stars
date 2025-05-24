/*
 * 权限校验hook
 */
import { toRefs } from 'vue'
import useUserStore from '@/store/modules/user'

const usePermission = () => {
  const userStore = useUserStore()
  const { ablePermissionButtons } = toRefs(userStore)

  // 单个功能权限判断
  function hasPermission(data) {
    if (!ablePermissionButtons.value) {
      return false
    }
    const isHave = ablePermissionButtons.value.includes(data)
    return isHave
  }

  // 基于role角色的权限判断
  // TODO 目前没有开发角色层面的校验权限，先不做具体实现
  function hasRolePermission(data) {
    console.log(data)
    return false
  }

  return { hasPermission, hasRolePermission }
}

export default usePermission
