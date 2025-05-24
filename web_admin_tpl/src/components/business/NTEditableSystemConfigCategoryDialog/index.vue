<template>
  <div class="nt-editable-system-config-category-dialog">
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
        v-loading="tbIsLoading"
        :validate-on-rule-change="false"
        :rules="formRules"
        :model="formMdl"
        label-width="130px"
      >
        <el-form-item prop="name" label="分类名称">
          <el-input
            v-model.trim="formMdl.name"
            clearable
            placeholder="请输入分类名称"
            class="w-4/5"
          ></el-input>
        </el-form-item>
        <el-form-item prop="pid" label="上级分类">
          <el-cascader
            v-model="formMdl.pid"
            :options="categoryListTree"
            :props="{ checkStrictly: true, value: 'id' }"
            filterable
            clearable
            placeholder="请选择上级分类"
            class="w-4/5"
          />
        </el-form-item>
        <el-form-item prop="sort">
          <template #label>
            排序
            <el-popover placement="top-start" width="auto" trigger="hover">
              <template #default>
                <p class="ml-[12px]">数值越小，分类越靠前</p>
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
import useCurrentInstance from '@/hooks/business/useCurrentInstance'
import formRules from './categoryRules'

export default defineComponent({
  name: 'NTEditableSystemConfigCategoryDialog',
  emits: ['add-success', 'edit-success', 'delete-success'],
  setup(props, { emit }) {
    const { $api, $apiCode, $message, $tree } = useCurrentInstance()
    const { recursionMachine } = $tree

    // 添加、编辑分类
    const categoryTreeMode = ref('add')
    const dialogFormVisible = ref(false)
    const hdTitle = computed(() => {
      return categoryTreeMode.value === 'edit' ? '编辑分类' : '添加分类'
    })

    // tab
    const activeTabName = ref('base')
    // 表单
    const tbIsLoading = ref(false)
    const loading = ref(false)
    const addFormRef = ref()
    const formMdl = ref({
      id: '',
      name: '',
      pid: [],
    })

    const treeData = ref([])
    const categoryListTree = computed(() => {
      const tempCategoryTree = cloneDeep(treeData.value)
      recursionMachine(tempCategoryTree, (dataItem) => {
        if (dataItem.id === formMdl.value.id) {
          dataItem.disabled = true
        }
      })

      tempCategoryTree[0].label = '顶级分类'
      return tempCategoryTree
    })
    const submitBtnTit = computed(() => {
      return categoryTreeMode.value === 'edit' ? '提交' : '添加'
    })

    // 添加
    const addCategory = async (data) => {
      const apiRes = await $api.systemConfigCategory.addSystemConfigCategoryApi(
        data
      )
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
    const editCategory = async (data) => {
      const apiRes =
        await $api.systemConfigCategory.editSystemConfigCategoryApi(data)
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
    const categoryDetail = async (id) => {
      const params = {
        id,
      }
      const apiRes =
        await $api.systemConfigCategory.systemConfigCategoryDetailApi(params)
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

    // 打开添加、编辑分类Dialog
    const open = async (data) => {
      const { categoryPids, mode, categoryTree } = data
      treeData.value = categoryTree
      categoryTreeMode.value = mode
      formMdl.value.pid = categoryPids
      dialogFormVisible.value = true

      nextTick(async () => {
        if (mode === 'edit') {
          formMdl.value.id = categoryPids[categoryPids.length - 1]
          await categoryDetail(formMdl.value.id)
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

      if (categoryTreeMode.value === 'add') {
        loading.value = true
        await addCategory(formData)
        emit('add-success')
        loading.value = false
      } else if (categoryTreeMode.value === 'edit') {
        formData.append('id', formMdl.value.id)
        await editCategory(formData)
        emit('edit-success')
      }
    }

    return {
      dialogFormVisible,
      hdTitle,
      formRules,
      tbIsLoading,
      activeTabName,
      loading,
      addFormRef,
      formMdl,
      categoryListTree,
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
<style lang="scss" scoped>
:deep(.el-upload) {
  border: 1px dashed #d9d9d9;
  border-radius: 6px;
  cursor: pointer;
  position: relative;
  &:hover {
    border-color: #409eff;
  }
  .icon-upload {
    font-size: 28px;
    color: #8c939d;
    width: 110px;
    height: 110px;
    line-height: 110px;
    text-align: center;
  }
}
</style>
