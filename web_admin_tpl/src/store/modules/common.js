/*
 * 公共相关状态
 */
import { ref } from 'vue'
import { defineStore } from 'pinia'
import $api from '@/api'
import { API_CODE as $apiCode } from '@/settings/config/http'

const useCommonStore = defineStore('useCommonStore', () => {
  // 配置数据
  const config = ref({
    systemConfig: {},
  })
  const updateSystemConfig = (storeFieldName, value) => {
    config.value[storeFieldName] = { ...config.value[storeFieldName], ...value }
  }

  // 获取系统配置
  const getSystemConfig = async (configKeysArr = [], storeFieldName = '') => {
    const params = {
      config_keys: configKeysArr,
    }
    const apiRes = await $api.common.systemConfigApi(params)
    const { status, data } = apiRes.data
    if (status === $apiCode.SUCCESS) {
      updateSystemConfig(storeFieldName, data)
    }
  }

  // 字典数据
  const dict = ref({})
  const updateDict = (value) => {
    dict.value = { ...dict.value, ...value }
  }

  // 获取字典
  const getDict = async (dicTypeKeys = []) => {
    const params = {
      dic_type_keys: dicTypeKeys,
    }
    const apiRes = await $api.common.dicListApi(params)
    const { status, data } = apiRes.data
    if (status === $apiCode.SUCCESS) {
      updateDict(data)
    }
  }

  return {
    config,
    updateSystemConfig,
    getSystemConfig,
    dict,
    updateDict,
    getDict,
  }
})

export default useCommonStore
