<template>
  <div class="page-container page-container--bg PAGE-MAIN-CONTENT">
    <el-card shadow="never" class="border-0">
      <el-tabs v-model="activeName" @tab-click="handleSwitchTab">
        <el-tab-pane
          v-for="config in systemConfCateList"
          :key="config.id"
          :label="config.label"
          :name="`${config.label}-${config.id}`"
        >
          <el-tabs v-model="activeName2" type="card">
            <el-tab-pane
              v-for="config2 in config.children"
              :key="config2.id"
              :label="config2.label"
              :name="`${config2.label}-${config2.id}`"
            >
              <formCreate
                v-model:api="formMdl"
                :option="formCreateOptions"
                :rule="formCreateRules"
              ></formCreate>
            </el-tab-pane>
          </el-tabs>
        </el-tab-pane>
      </el-tabs>
    </el-card>
  </div>
</template>
<script>
import { defineComponent, ref, onMounted, computed } from 'vue'
import useCurrentInstance from '@/hooks/business/useCurrentInstance'
import { ROOT_NODE_PID } from '@/components/NTEditableTree/index.vue'
import { cloneDeep } from 'lodash-es'
import formCreate from '@form-create/element-ui'

export default defineComponent({
  components: {
    formCreate: formCreate.$form(),
  },
  setup() {
    const { $api, $apiCode, $message, $tree } = useCurrentInstance()
    const { getTree, formateTree } = $tree

    // 获取设置tabs菜单
    const activeName = ref('')
    const activeName2 = ref('')
    const loading = ref(false)
    const systemConfCateList = ref([])
    const treeDataKeyMap = {
      id: 'id',
      label: 'name',
      pid: 'pid',
      children: 'children',
    }
    const systemConfigCategoryListByRid = async () => {
      const params = {
        rid: 1,
      }
      loading.value = true
      const apiRes =
        await $api.systemConfigCategory.systemConfigCategoryListByRidApi(params)
      const { status, data, message } = apiRes.data
      if (status === $apiCode.SUCCESS) {
        systemConfCateList.value = []
        const treeArr = []
        getTree(treeArr, data, { id: 'id', pid: 'pid', name: 'name' }, [], 1)
        const allTreeNodeData = [
          {
            id: 1,
            pid: ROOT_NODE_PID,
            name: '全部配置分类',
            children: treeArr || [],
          },
        ]
        formateTree(systemConfCateList.value, allTreeNodeData, treeDataKeyMap)

        systemConfCateList.value = systemConfCateList.value[0].children
      } else {
        $message.error({
          message,
          duration: 3000,
        })
      }
      loading.value = false
    }

    const formMdl = ref({})
    const systemConfList = ref([])
    const formCreateOptions = {
      onSubmit: (params) => {
        settingSaveSystemConfig(params)
      },
    }
    const formCreateRules = computed(() => {
      return cloneDeep(systemConfList.value)
    })
    const systemConfigListByCid = async (cid) => {
      const params = {
        cid,
      }
      const apiRes = await $api.systemConfig.systemConfigListByCidApi(params)
      const { status, data, message } = apiRes.data
      if (status === $apiCode.SUCCESS) {
        systemConfList.value = data.map((item) => {
          item = item.props
          return item
        })
      } else {
        $message.error({
          message,
          duration: 3000,
        })
      }
    }
    const handleSwitchTab = (tab) => {
      const curConfigCate = systemConfCateList.value[tab.index]
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
      if (
        systemConfCateList.value[0].children &&
        systemConfCateList.value[0].children[0]
      ) {
        activeName2.value = `${systemConfCateList.value[0].children[0].label}-${systemConfCateList.value[0].children[0].id}`

        systemConfigListByCid(systemConfCateList.value[0].children[0].id)
      }
    })

    // 提交配置数据
    const settingSaveSystemConfig = async (params) => {
      const apiRes = await $api.systemConfig.settingSaveSystemConfigApi(params)
      const { status, message } = apiRes.data
      if (status === $apiCode.SUCCESS) {
        $message({
          type: 'success',
          message,
          duration: 3000,
        })
      } else {
        $message({
          type: 'warning',
          message,
          duration: 3000,
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
      systemConfigListByCid,
    }
  },
})
</script>
<style lang="scss" scoped></style>
