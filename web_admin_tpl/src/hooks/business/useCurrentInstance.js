/*
 * 当前组件实例hooks，提供常用工具等
 */
import { getCurrentInstance } from 'vue'
import { useRouter, useRoute } from 'vue-router'

const useCurrentInstance = () => {
  const router = useRouter()
  const route = useRoute()

  const currentInstance = getCurrentInstance()
  const { proxy } = currentInstance
  const {
    $api,
    $apiCode,
    $message,
    $msgbox,
    $confirm,
    $dict,
    $config,
    $is,
    $tree,
    $object,
    $date,
    $bus,
    $download,
  } = proxy

  return {
    router,
    route,
    currentInstance,
    proxy,
    $api,
    $apiCode,
    $message,
    $msgbox,
    $confirm,
    $dict,
    $config,
    $is,
    $tree,
    $object,
    $date,
    $bus,
    $download,
  }
}

export default useCurrentInstance
