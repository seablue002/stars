import { ref } from 'vue'
import { defineStore } from 'pinia'

const useLayoutStore = defineStore('useLayoutStore', () => {
  // 框架layouts头部区域是否fixed定位模式
  const isHeaderFixed = ref(true)
  const updateHeaderFixed = (status) => {
    isHeaderFixed.value = status
  }

  // 是否全屏
  const isFullScreen = ref(false)
  const updateFullScreenStatus = (status) => {
    isFullScreen.value = status
  }

  // 导航菜单区域是否折叠状态
  const isNavMenuFold = ref(false)
  const updateNavMenuFoldStatus = (status) => {
    isNavMenuFold.value = status
  }

  // 需要做离开确认提示页面离开时，是否已经确认离开
  const isSureConfirmLeave = ref(false)
  const updateIsSureConfirmLeave = (status) => {
    isSureConfirmLeave.value = status
  }

  return {
    isHeaderFixed,
    updateHeaderFixed,
    isFullScreen,
    updateFullScreenStatus,
    isNavMenuFold,
    updateNavMenuFoldStatus,
    isSureConfirmLeave,
    updateIsSureConfirmLeave,
  }
})

export default useLayoutStore
