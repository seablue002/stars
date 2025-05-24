import { ref, inject } from 'vue'
import { defineStore } from 'pinia'
import router from '@/router'

const useAppStore = defineStore('useAppStore', () => {
  const $array = inject('$array')
  // 当前网络环境状态
  const networkStatus = ref('')
  const updateNetworkStatus = (status) => {
    networkStatus.value = status
  }

  // 路由历史记录
  const visitedTags = ref([])

  // 追加一个历史记录tag
  const addOneVisitedTags = (history) => {
    if (
      $array.indexOfObjInObjArrByKey(history, visitedTags.value, 'fullPath') ===
      -1
    ) {
      visitedTags.value.push(history)
    }
  }

  /**
   * 删除一个历史记录tag
   * @param {*} history 需要管理的历史route
   * @param {*} autoOpenDefaultPage 是否在关闭route后自动打开一个默认route页面
   * @returns
   */
  const removeOneVisitedTags = (history, autoOpenDefaultPage = true) => {
    const visitedTagsLst = visitedTags.value.filter((tag) => {
      return tag.meta.closeable !== false
    })

    const index = $array.indexOfObjInObjArrByKey(
      history,
      visitedTagsLst,
      'fullPath'
    )

    if (index !== -1) {
      visitedTagsLst.splice(index, 1)
      visitedTags.value = visitedTagsLst

      if (!autoOpenDefaultPage) {
        return
      }

      const len = visitedTagsLst.length
      if (len) {
        const nextHistoryTag = visitedTagsLst[len - 1]
        if (history.fullPath !== nextHistoryTag.fullPath) {
          router.push(nextHistoryTag)
        }
      } else {
        router.push('/')
      }
    }
  }

  // 删除其它历史记录tag
  const removeOtherVisitedTags = (history) => {
    let visitedTagsLst = visitedTags.value.filter((tag) => {
      return tag.meta.closeable !== false
    })

    const index = $array.indexOfObjInObjArrByKey(
      history,
      visitedTagsLst,
      'fullPath'
    )
    if (index !== -1 || history.meta.notCloseable) {
      visitedTagsLst = visitedTagsLst.filter((tag) => {
        return tag.fullPath === history.fullPath
      })
      visitedTags.value = visitedTags.value
        .filter((tag) => {
          return tag.meta.closeable === false
        })
        .concat(visitedTagsLst)

      router.push(history.fullPath)
    }
  }

  // 删除所有历史记录tag
  const removeAllVisitedTags = (history) => {
    const visitedTagsLst = visitedTags.value.filter((tag) => {
      return tag.meta.closeable !== false
    })

    const index = $array.indexOfObjInObjArrByKey(
      history,
      visitedTagsLst,
      'fullPath'
    )

    if (index !== -1 || history.meta.closeable !== true) {
      visitedTags.value = visitedTags.value.filter((tag) => {
        return tag.meta.closeable === false
      })

      router.push('/')
    }
  }

  // 清空app相关store数据
  const clear = () => {
    visitedTags.value = []
  }

  return {
    networkStatus,
    updateNetworkStatus,
    visitedTags,
    addOneVisitedTags,
    removeOneVisitedTags,
    removeOtherVisitedTags,
    removeAllVisitedTags,
    clear,
  }
})

export default useAppStore
