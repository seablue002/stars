import { ref, provide, onActivated } from 'vue'
import { onBeforeRouteLeave, useRoute } from 'vue-router'
const useFixKeepAliveListRefresh = {
  // 记录是否需要做列表刷新
  recode: (): any => {
    // 内容是否进过了添加、编辑产生了变化
    // 为回退列表页是否刷新做好标记
    const isNeedRefreshLstPage = ref(false)
    provide('isNeedRefreshLstPage', isNeedRefreshLstPage)

    onBeforeRouteLeave((to, from, next) => {
      to.params = { ...to.params, isNeedRefreshLstPage: isNeedRefreshLstPage.value as any }
      isNeedRefreshLstPage.value = false
      next()
    })
    return {
      isNeedRefreshLstPage
    }
  },
  // 执行刷新
  executeRefreshJudgment: (callback: () => any): void => {
    const route = useRoute()
    // 缓存页面被激活时
    onActivated(() => {
      // 如果有成功执行添加、编辑回退到列表时执行重新拉取列表数据
      // 以保证展示最新数据，修正keep-alive引起的问题
      if (route.params.isNeedRefreshLstPage) {
        callback()
      }
    })
  }
}

export default useFixKeepAliveListRefresh
