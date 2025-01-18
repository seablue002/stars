<template>
  <div class="app-weapp PAGE-MAIN-CONTENT" v-loading="loading">
    <formCreate :option="formCreateOptions" :rule="formCreateRules" v-model="formMdl"></formCreate>
  </div>
</template>
<script lang="ts">
import { defineComponent, ref, getCurrentInstance, computed } from 'vue'
import formCreate from '@form-create/element-ui'
import { HTTP_CONFIG } from '@/config/http'
import useAutoMainContentHeight from '@/hooks/useAutoMainContentHeight'
import { TreeProps } from '@/components/EditableTree.vue'
import {
  SystemConfigListByCidProps,
  systemConfigListByCidApi,
  SettingSaveSystemConfigProps,
  settingSaveSystemConfigApi
} from '@/api/systemConfig'
import _ from 'lodash'
export default defineComponent({
  components: {
    formCreate: formCreate.$form()
  },
  setup () {
    const { proxy } = (getCurrentInstance() as any)
    useAutoMainContentHeight()
    const loading = ref(false)

    const formMdl = ref({})
    const systemConfList = ref<TreeProps[]>([])
    const formCreateOptions = {
      onSubmit: (params: SettingSaveSystemConfigProps) => {
        settingSaveSystemConfig(params)
      }
    }
    const formCreateRules = computed(() => {
      return _.cloneDeep(systemConfList.value)
    })
    const systemConfigListByCid = async (cid: string|number) => {
      const params: SystemConfigListByCidProps = {
        cid
      }
      loading.value = true
      const { status, data, message } = await systemConfigListByCidApi(params)
      if (status === HTTP_CONFIG.API_SUCCESS_CODE) {
        systemConfList.value = data.map((item: any) => {
          /* eslint-disable */
          item.options = item.options ? eval('(' + item.options + ')') : []
          item.props = item.props ? eval('(' + item.props + ')') : {}
          item.validate = item.validate ? eval('(' + item.validate + ')') : []
          return item
        })
      } else {
        proxy.message.error({
          message,
          duration: 3000
        })
      }
      loading.value = false
    }
    systemConfigListByCid(7)
    
    // 提交配置数据
    const settingSaveSystemConfig = async (params: SettingSaveSystemConfigProps) => {
      const { status, message } = await settingSaveSystemConfigApi(params)
      if (status === HTTP_CONFIG.API_SUCCESS_CODE) {
        proxy.message({
          type: 'success',
          message,
          duration: 3000
        })
      } else {
        proxy.message({
          type: 'warning',
          message,
          duration: 3000
        })
      }
    }

    return {
      loading,
      formMdl,
      systemConfList,
      formCreateOptions,
      formCreateRules,
      systemConfigListByCid
    }
  }
})
</script>
<style lang="scss" scoped>
.app-weapp {
  padding: 10px 20px;
  background-color: #fff;
  border-radius: 4px;
}
</style>
