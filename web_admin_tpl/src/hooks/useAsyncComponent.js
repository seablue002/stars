/**
 * 加载异步组件
 */
import { defineAsyncComponent } from 'vue'
import { isFunction } from '@/utils/helper/is'

const useAsyncComponent = (options) => {
  const { component, wait, timeout } = options

  const AsyncComponent = defineAsyncComponent({
    // 加载函数
    loader: async () => {
      await loadDelay(wait)
      return component()
    },
    // 超时
    timeout,
  })

  const loadDelay = async (fn) => {
    if (!isFunction(fn)) {
      throw new Error('fn需为function函数')
    }
    await fn()
  }

  return {
    AsyncComponent,
  }
}

export default useAsyncComponent
