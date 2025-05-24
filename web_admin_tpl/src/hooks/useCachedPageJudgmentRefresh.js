import { ref, provide, onActivated } from 'vue'
import { onBeforeRouteLeave, useRoute } from 'vue-router'

const useCachedPageJudgmentRefresh = () => {
  // 为回退刷新做好标记
  const isNeedRefreshPage = ref(false)
  provide('isNeedRefreshPage', isNeedRefreshPage)

  const pageRefreshRecode = () => {
    onBeforeRouteLeave((to, from, next) => {
      to.params = {
        ...to.params,
        isNeedRefreshPage: isNeedRefreshPage.value,
      }
      isNeedRefreshPage.value = false
      next()
    })
  }

  // 执行刷新
  const executeRefreshJudgment = (callback) => {
    const route = useRoute()
    // 缓存页面被激活时
    onActivated(() => {
      // 判断是否需要刷新
      if (route.params.isNeedRefreshPage) {
        callback()
      }
    })
  }
  return {
    isNeedRefreshPage,
    pageRefreshRecode,
    executeRefreshJudgment,
  }
}

export default useCachedPageJudgmentRefresh
