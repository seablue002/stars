<template>
  <div class="nt-editable-label-dialog">
    <el-dialog
      v-model="dialogFormVisible"
      :title="hdTitle"
      width="1000px"
      :lock-scroll="true"
      draggable
      class="custom-dialog"
      @close="handleClose"
    >
      <el-form
        ref="addFormRef"
        v-loading="loading"
        :validate-on-rule-change="false"
        :rules="labelRules"
        :model="formMdl"
        label-width="130px"
      >
        <el-form-item prop="name" label="标签名称">
          <el-input
            v-model.trim="formMdl.name"
            clearable
            placeholder="请输入标签名称"
            class="w-4/5"
          ></el-input>
        </el-form-item>
        <el-form-item prop="pid" label="上级标签">
          <el-cascader
            v-model="formMdl.pid"
            :options="labelListTree"
            :props="{ checkStrictly: true, value: 'id' }"
            filterable
            clearable
            placeholder="请选择上级标签"
            class="w-4/5"
          />
        </el-form-item>
        <el-form-item prop="sort">
          <template #label>
            排序
            <el-popover placement="top-start" width="auto" trigger="hover">
              <template #default>
                <p class="ml-[12px]">数值越小，标签越靠前</p>
              </template>
              <template #reference>
                <i class="ri-question-line ml-[4px]"></i>
              </template>
            </el-popover>
          </template>
          <el-input
            v-model="formMdl.sort"
            clearable
            placeholder="请输入排序"
            class="w-4/5"
          ></el-input>
        </el-form-item>
        <el-form-item>
          <el-button @click="handleRest">重置</el-button>
          <el-button v-loading="loading" type="primary" @click="handleSubmit">{{
            submitBtnTit
          }}</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>
<script>
import { defineComponent, ref, computed, nextTick } from 'vue'
import { cloneDeep } from 'lodash-es'
import { API_HOST } from '@/settings/config/http'
import useCurrentInstance from '@/hooks/business/useCurrentInstance'
import labelRules from './labelRules'

export default defineComponent({
  name: 'NTEditableLabelDialog',
  emits: ['add-success', 'edit-success', 'delete-success'],
  setup(props, { emit }) {
    const { $api, $apiCode, $message, $tree } = useCurrentInstance()
    const { recursionMachine } = $tree

    // 添加、编辑标签
    const labelTreeMode = ref('add')
    const dialogFormVisible = ref(false)
    const hdTitle = computed(() => {
      return labelTreeMode.value === 'edit' ? '编辑标签' : '添加标签'
    })

    // 表单
    const loading = ref(false)
    const addFormRef = ref()
    const formMdl = ref({
      id: '',
      name: '',
      pid: [],
      sort: '',
    })

    const treeData = ref([])
    const labelListTree = computed(() => {
      const tempLabelTree = cloneDeep(treeData.value)
      recursionMachine(tempLabelTree, (dataItem) => {
        if (dataItem.id === formMdl.value.id) {
          dataItem.disabled = true
        }
      })

      tempLabelTree[0].label = '顶级标签'
      return tempLabelTree
    })
    const submitBtnTit = computed(() => {
      return labelTreeMode.value === 'edit' ? '提交' : '添加'
    })

    // 添加
    const addLabel = async (data) => {
      const apiRes = await $api.label.addLabelApi(data)
      const { status, message } = apiRes.data
      if (status === $apiCode.SUCCESS) {
        resetForm()
        $message.success({
          message,
          duration: 3000,
        })
      } else {
        $message.warning({
          message,
          duration: 3000,
        })
      }
    }

    // 编辑
    const editLabel = async (data) => {
      const apiRes = await $api.label.editLabelApi(data)
      const { status, message } = apiRes.data
      if (status === $apiCode.SUCCESS) {
        $message.success({
          message,
          duration: 3000,
        })
      } else {
        $message.warning({
          message,
          duration: 3000,
        })
      }
    }

    // 详情
    const labelDetail = async (id) => {
      const params = {
        id,
      }
      const apiRes = await $api.label.labelDetailApi(params)
      const { status, data, message } = apiRes.data
      if (status === $apiCode.SUCCESS) {
        formMdl.value = {
          ...data,
        }
      } else {
        $message.warning({
          message,
          duration: 3000,
        })
      }
    }

    // 打开添加、编辑标签Dialog
    const open = async (data) => {
      const { labelPids, mode, labelTree } = data
      treeData.value = labelTree
      labelTreeMode.value = mode
      formMdl.value.pid = labelPids
      dialogFormVisible.value = true

      nextTick(async () => {
        if (mode === 'edit') {
          formMdl.value.id = labelPids[labelPids.length - 1]
          await labelDetail(formMdl.value.id)
        }
      })
    }

    // 关闭弹窗
    const close = () => {
      dialogFormVisible.value = false
    }

    // 关闭弹窗回调
    const handleClose = () => {
      resetForm()
      formMdl.value.id = ''
    }

    // 重置
    const handleRest = () => {
      resetForm()
    }

    const resetForm = async () => {
      addFormRef.value.resetFields()
    }

    // 提交
    const handleSubmit = async () => {
      const validateStatus = await new Promise((resolve) => {
        addFormRef.value.validate(resolve)
      })

      if (!validateStatus || loading.value) {
        return
      }

      const params = {
        name: formMdl.value.name,
        pid: formMdl.value.pid,
        sort: formMdl.value.sort,
      }
      const formData = new FormData()
      Object.entries(params).forEach(([attr, value]) => {
        formData.append(attr, value)
      })

      if (labelTreeMode.value === 'add') {
        loading.value = true
        await addLabel(formData)
        emit('add-success')
        loading.value = false
      } else if (labelTreeMode.value === 'edit') {
        formData.append('id', formMdl.value.id)
        await editLabel(formData)
        emit('edit-success')
      }
    }

    return {
      API_HOST,
      dialogFormVisible,
      hdTitle,
      labelRules,
      loading,
      addFormRef,
      formMdl,
      labelListTree,
      submitBtnTit,
      handleRest,
      handleSubmit,
      open,
      close,
      handleClose,
    }
  },
})
</script>
<style lang="scss" scoped></style>
