/*
 * 离开页面守卫
 */
import { ElMessageBox } from 'element-plus'
import useLayoutStore from '@/store/modules/layout'
import { NEED_CONFIRM_PAGE_ROUTE_PATH_LIST } from '@/settings/config/router'

export default function pageLeaveGuard(router) {
  const layoutStore = useLayoutStore()
  router.beforeEach((to, from, next) => {
    if (
      NEED_CONFIRM_PAGE_ROUTE_PATH_LIST.includes(from.path) &&
      !layoutStore.isSureConfirmLeave
    ) {
      setTimeout(() => {
        ElMessageBox.confirm('内容未保存，确认退出？', '提示', {
          type: 'warning',
        })
          .then(async () => {
            setTimeout(() => {
              window.ntVisitedTagsSwitchBus.emit(true)
              layoutStore.updateIsSureConfirmLeave(true)
              window.ntVisitedTagsBus.emit([from, false])
            }, 50)
            next()
          })
          .catch(() => {
            next(false)
          })
      }, 200)
    } else {
      next()
    }
  })

  router.afterEach((to) => {
    if (
      NEED_CONFIRM_PAGE_ROUTE_PATH_LIST.includes(to.path) &&
      layoutStore.isSureConfirmLeave
    ) {
      // 重新进入离开需要做离开确认提示页面后，重置是否允许离开页面标志状态值
      layoutStore.updateIsSureConfirmLeave(false)
    }
  })
}
