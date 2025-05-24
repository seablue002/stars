import { computed } from 'vue'
import { useRoute } from 'vue-router'
import { isFunction } from '@/utils/helper/is'

const useAddPage = (props, config = { callback: null }) => {
  const { callback = null } = config
  const route = useRoute()

  // 页面模式
  const pageMode = computed(() => {
    // 1、如果传递了props.mode，则优先使用props.mode
    if (props.mode) {
      return props.mode
    }

    if (isFunction(callback)) {
      return callback()
    }

    // 正则识别path路径最后是否存在/add、/edit、/detail
    const regex = /\/(add|edit|detail)$/

    // 获取匹配的模式
    const match = route.path.match(regex)
    // 如果匹配到模式，则返回匹配到的模式，否则返回props传入的mode
    if (match) {
      return match[1]
    }

    return 'add'
  })

  // 是否是详情模式
  const isDetailMode = computed(() => {
    return ['detail'].includes(pageMode.value)
  })

  // 是否新增模式
  const isCreateMode = computed(() => {
    return ['add'].includes(pageMode.value)
  })

  // 是否编辑模式
  const isEditMode = computed(() => {
    return ['edit'].includes(pageMode.value)
  })

  return {
    pageMode,
    isDetailMode,
    isCreateMode,
    isEditMode,
  }
}

export default useAddPage
