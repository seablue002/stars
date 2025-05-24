// 页面主内容高度自动调整
import { onMounted, ref } from 'vue'

const useAutoMainContentHeight = () => {
  const mainContentDomH = ref('0')

  onMounted(() => {
    const mainContentDom = document.querySelector('.PAGE-MAIN-CONTENT')
    const clientRectTop = mainContentDom?.getBoundingClientRect().top
    mainContentDomH.value = `calc(100vh - ${clientRectTop}px - 30px)`
    mainContentDom.style.minHeight = mainContentDomH.value
  })

  return {
    mainContentDomH,
  }
}

export default useAutoMainContentHeight
