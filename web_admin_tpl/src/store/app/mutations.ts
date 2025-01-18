import router from '@/router'
import COMMON_TAGS_PATH from '@/config/commonHistoryTags'
import { indexOfObjInObjArrByKey, triggleWindowSystemEvent } from '@/utils/index'
import { AppProps } from './index'
import { RouteLocationNormalized } from 'vue-router'

export default {
  // 设置控制sidebar左侧导航菜单折叠与否的state值
  setSidebarIsCloseVal (state: AppProps, status: boolean): void {
    state.sidebarIsClose = status
    localStorage.setItem('sidebarIsClose', String(status))

    // 手动触发window-resize事件，让依赖于此事件的功能做自动调整更新
    triggleWindowSystemEvent('resize')
  },
  // 从本地存储提取设置控制sidebar左侧导航菜单折叠与否的值到state
  initSidebarIsCloseVal (state: AppProps): void {
    const status = localStorage.getItem('sidebarIsClose')
    state.sidebarIsClose = (status === 'true')
  },
  // 设置sidebar宽度
  setSidebarWVal (state: AppProps, width: string): void {
    state.sidebarW = width
    localStorage.setItem('sidebarW', width)
  },
  initSidebarWVal (state: AppProps): void {
    const w = localStorage.getItem('sidebarW')
    state.sidebarW = w || '210px'
  },
  // 追加一个历史记录tag
  addRouterHistoryTagsVal (state: AppProps, history: RouteLocationNormalized): void {
    if (indexOfObjInObjArrByKey(history, state.routerHistoryTags, 'fullPath') === -1) {
      state.routerHistoryTags.push(history)
    }
  },
  // 删除一个历史记录tag
  removeRouterHistoryTagsVal (state: AppProps, history: RouteLocationNormalized): void {
    const routerHistoryTags = state.routerHistoryTags.filter((tag) => {
      return !COMMON_TAGS_PATH.includes(tag.fullPath)
    })

    const index = indexOfObjInObjArrByKey(history, routerHistoryTags, 'fullPath')

    if (index !== -1) {
      routerHistoryTags.splice(index, 1)
      state.routerHistoryTags = routerHistoryTags
      const len = routerHistoryTags.length
      if (len) {
        const nextHistoryTag = routerHistoryTags[len - 1]
        if (history.fullPath !== nextHistoryTag.fullPath) {
          router.push(nextHistoryTag)
        }
      } else {
        router.push('/')
      }
    }
  },
  // 删除所有历史记录tag
  removeAllRouterHistoryTagsVal (state: AppProps, history: RouteLocationNormalized): void {
    const routerHistoryTags = state.routerHistoryTags.filter((tag) => {
      return !COMMON_TAGS_PATH.includes(tag.fullPath)
    })

    const index = indexOfObjInObjArrByKey(history, routerHistoryTags, 'fullPath')

    if (index !== -1 || COMMON_TAGS_PATH.includes(history.fullPath)) {
      routerHistoryTags.splice(0, routerHistoryTags.length)
      state.routerHistoryTags = routerHistoryTags
      router.push('/')
    }
  },
  // 删除其它历史记录tag
  removeOtherRouterHistoryTagsVal (state: AppProps, history: RouteLocationNormalized): void {
    let routerHistoryTags = state.routerHistoryTags.filter((tag) => {
      return !COMMON_TAGS_PATH.includes(tag.fullPath)
    })

    const index = indexOfObjInObjArrByKey(history, routerHistoryTags, 'fullPath')
    if (index !== -1 || COMMON_TAGS_PATH.includes(history.fullPath)) {
      routerHistoryTags = routerHistoryTags.filter((tag) => {
        return tag.fullPath === history.fullPath
      })
      state.routerHistoryTags = routerHistoryTags
      router.push(history.fullPath)
    }
  },
  // 刷新当前历史记录tag页面
  refreshRouterHistoryTagsVal (state: AppProps, history: RouteLocationNormalized): void {
    router.replace({ path: history.fullPath })
  },
  // 清空访问历史记录tags集合
  clearRouterHistoryTags (state: AppProps): void {
    state.routerHistoryTags = []
  }
}
