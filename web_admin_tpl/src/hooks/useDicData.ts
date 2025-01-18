import { ref, getCurrentInstance } from 'vue'
import { HTTP_CONFIG } from '@/config/http'
import { DicListProps, dicListApi } from '@/api/common'
const useDicData = async (dicTypeKeys: string[]): Promise<{[key: string]: any}> => {
  const { proxy } = (getCurrentInstance() as any)
  const dicList = ref([])
  const params: DicListProps = {
    dic_type_keys: dicTypeKeys
  }
  const { status, data, message } = await dicListApi(params)
  if (status === HTTP_CONFIG.API_SUCCESS_CODE) {
    dicList.value = data
  } else {
    proxy.message({
      type: 'warning',
      message,
      duration: 3000
    })
  }
  return {
    ...dicList.value
  }
}

export default useDicData
