<template>
  <div class="base-form">
    <el-form ref="formRef" :model="formMdl" label-width="100px">
      <el-form-item prop="label" label="标签设置">
        <template #label>
          标签设置
          <el-popover placement="top-start" width="auto" trigger="hover">
            <template #default>
              <p class="ml-[12px]">可用于信息分类、标签展示</p>
            </template>
            <template #reference>
              <i class="ri-question-line ml-[4px]"></i>
            </template>
          </el-popover>
        </template>
        <el-tree
          ref="labelTreeRef"
          :data="labelBaseInfoList"
          show-checkbox
          node-key="id"
          :expand-on-click-node="false"
          :check-on-click-node="true"
          default-expand-all
          class="sm:w-1/1 md:w-1/1 lg:w-2/3 xl:w-3/5 2xl:w-2/5"
          @check-change="handleGetCheckedNodes"
        >
        </el-tree>
      </el-form-item>
    </el-form>
  </div>
</template>
<script>
import { ref } from 'vue'
import useCurrentInstance from '@/hooks/business/useCurrentInstance'
import useAddPage from '@/hooks/business/useAddPage'
import { cloneDeep } from 'lodash-es'

export default {
  setup(props) {
    const { isDetailMode } = useAddPage(props)
    const { $api, $apiCode, $message, $tree } = useCurrentInstance()
    const { getTree: getLabelTree } = $tree

    // 表单
    const formRef = ref(null)

    const initFormMdl = {
      label: [],
    }
    const formMdl = ref({ ...cloneDeep(initFormMdl) })

    const labelTreeRef = ref()
    // 获取标签列表
    const labelBaseInfoList = ref([])
    const getLabelBaseInfoList = async () => {
      const apiRes = await $api.label.labelBaseInfoListApi()
      const { status, data, message } = apiRes.data
      if (status === $apiCode.SUCCESS) {
        let labelList = data
        if (isDetailMode.value) {
          labelList = data.map((item) => {
            item.disabled = true
            return item
          })
        }
        getLabelTree(
          labelBaseInfoList.value,
          labelList,
          {
            id: 'id',
            label: 'name',
            pid: 'pid',
            disabled: 'disabled',
          },
          [0]
        )
      } else {
        $message.warning({
          message,
          duration: 3000,
        })
      }
    }

    // 获取选中的标签id
    const handleGetCheckedNodes = () => {
      // 获取选中的节点
      // 获取当前的选中的数据{对象}
      const checkedNodes = labelTreeRef.value.getCheckedNodes(false, true)

      const checkedLabelIds = checkedNodes.map((item) => {
        return item.id
      })
      formMdl.value.label = checkedLabelIds
    }

    const handleReset = () => {
      formRef.value.resetFields()
      labelTreeRef.value.setCheckedKeys([], false)
    }

    return {
      formRef,
      formMdl,
      isDetailMode,
      labelTreeRef,
      labelBaseInfoList,
      getLabelBaseInfoList,
      handleGetCheckedNodes,
      handleReset,
    }
  },
}
</script>
<style lang="scss" scoped></style>
