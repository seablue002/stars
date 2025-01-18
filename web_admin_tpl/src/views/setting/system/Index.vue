<template>
  <div class="setting-system PAGE-MAIN-CONTENT" v-loading="loading">
    <el-tabs
      v-model="activeName"
      @tab-click="handleSwitchTab">
      <el-tab-pane
        v-for="config in systemConfCateList"
        :key="config.id"
        :label="config.label"
        :name="`${config.label}-${config.id}`">
        <el-tabs v-model="activeName2" type="card">
          <el-tab-pane
            v-for="config2 in config.children"
            :key="config2.id"
            :label="config2.label"
            :name="`${config2.label}-${config2.id}`">
            <formCreate :option="formCreateOptions" :rule="formCreateRules" v-model="formMdl"></formCreate>
          </el-tab-pane>
        </el-tabs>
      </el-tab-pane>
    </el-tabs>
  </div>
</template>
<script lang="ts">
import { defineComponent, ref, getCurrentInstance, onMounted, computed } from 'vue'
import formCreate from '@form-create/element-ui'
import { HTTP_CONFIG } from '@/config/http'
import { getTree, formateTree } from '@/utils'
import useAutoMainContentHeight from '@/hooks/useAutoMainContentHeight'
import type { TabsPaneContext } from 'element-plus'
import { TreeProps, TreeDataKeyMapProps, ROOT_NODE_PID } from '@/components/EditableTree.vue'
import { systemConfigCategoryListByRidApi } from '@/api/systemConfigCategory'
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
    // 获取设置tabs菜单
    const activeName = ref('')
    const activeName2 = ref('')
    const loading = ref(false)
    const systemConfCateList = ref<TreeProps[]>([])
    const treeDataKeyMap: TreeDataKeyMapProps = { id: 'id', label: 'name', pid: 'pid', children: 'children' }
    const systemConfigCategoryListByRid = async () => {
      const params = {
        rid: 1
      }
      loading.value = true
      const { status, data, message } = await systemConfigCategoryListByRidApi(params)
      if (status === HTTP_CONFIG.API_SUCCESS_CODE) {
        systemConfCateList.value = []
        const treeArr: TreeProps[] = []
        getTree(treeArr, data, 1)
        const allTreeNodeData = [{ id: 1, pid: ROOT_NODE_PID, name: '全部配置分类', children: treeArr || [] }]
        formateTree((systemConfCateList.value as any), allTreeNodeData, (treeDataKeyMap as any))

        systemConfCateList.value = (systemConfCateList.value[0].children as any)
      } else {
        proxy.message.error({
          message,
          duration: 3000
        })
      }
      loading.value = false
    }

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
    }
    const handleSwitchTab = (tab: TabsPaneContext) => {
      const curConfigCate = systemConfCateList.value[(tab.index as any)]
      if (curConfigCate && curConfigCate.children) {
        activeName2.value = `${curConfigCate.children[0].label}-${curConfigCate.children[0].id}`
        systemConfigListByCid(curConfigCate.children[0].id)
      }
    }
    onMounted(async () => {
      await systemConfigCategoryListByRid()
      if (systemConfCateList.value[0]) {
        activeName.value = `${systemConfCateList.value[0].label}-${systemConfCateList.value[0].id}`
      }
      if (systemConfCateList.value[0].children && systemConfCateList.value[0].children[0]) {
        activeName2.value = `${systemConfCateList.value[0].children[0].label}-${systemConfCateList.value[0].children[0].id}`
        systemConfigListByCid(systemConfCateList.value[0].children[0].id)
      }
    })
    
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
      activeName,
      activeName2,
      loading,
      systemConfCateList,
      handleSwitchTab,
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
.setting-system {
  padding: 10px 20px;
  background-color: #fff;
  border-radius: 4px;
}
</style>
