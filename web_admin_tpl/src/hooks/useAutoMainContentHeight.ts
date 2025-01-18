// 页面主内容高度自动调整
import { onMounted, ref } from 'vue'
const useAutoMainContentHeight = (): {[key: string]: any} => {
  const mainContentDomH = ref('0')
  onMounted(() => {
    const mainContentDom = (document.querySelector('.PAGE-MAIN-CONTENT') as HTMLElement)
    const clientRectTop = mainContentDom?.getBoundingClientRect().top
    const copyrightDom = ((document.querySelector('.copyright') as HTMLElement))
    const copyrightDomH = copyrightDom?.offsetHeight
    mainContentDomH.value = `calc(100vh - ${clientRectTop}px - ${copyrightDomH}px)`
    mainContentDom.style.minHeight = mainContentDomH.value
  })
  return {
    mainContentDomH
  }
}

export default useAutoMainContentHeight
