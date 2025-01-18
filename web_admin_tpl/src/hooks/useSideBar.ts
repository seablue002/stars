import { computed } from 'vue'
import { useStore } from 'vuex'
import { GlobalDataProps } from '@/store/index'

const useSideBar = (): {[key: string]: any} => {
  const store = useStore<GlobalDataProps>()
  const sidebarIsClose = computed(() => store.state.app.sidebarIsClose)
  const sidebarW = computed(() => store.state.app.sidebarW)
  return {
    sidebarIsClose: sidebarIsClose,
    sidebarW: sidebarW
  }
}

export default useSideBar
