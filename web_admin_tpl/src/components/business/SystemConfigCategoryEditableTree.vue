<template>
  <div class="category-editable-tree">
    <EditableTree
      ref="editableTreeRef"
      v-loading="treeIsLoading"
      :treeData="treeData"
      :styleConf="{ width: '500px' }"
      :mode="mode"
      @add-category="handleOpenAddDialog"
      @edit-category="handleOpenEditDialog"
      @delete-category="handleDeleteSystemConfigCategory"
      @select-row="handleSelectRow">
    </EditableTree>
    <el-dialog
      v-model="dialogFormVisible"
      :title="hdTitle"
      width="800px"
      :lock-scroll="true"
      @close="handleClose">
      <el-form
        ref="addFormRef"
        :rules="formRules"
        :model="formMdl"
        v-loading="tbIsLoading"
        label-width="100px">
        <el-form-item prop="categoryName" label="分类名称">
          <el-input v-model.trim="formMdl.categoryName" clearable placeholder="请输入分类名称"></el-input>
        </el-form-item>
        <el-form-item prop="parentCategory" label="上级分类">
          <el-cascader v-model="formMdl.parentCategory" :options="categoryListTree" :props="{ checkStrictly: true, value: 'id' }" filterable clearable placeholder="请选择上级分类" />
        </el-form-item>
        <el-form-item prop="sort" label="排序" class="small-input">
          <el-input v-model="formMdl.sort" clearable placeholder="请输入排序"></el-input>
        </el-form-item>
        <el-form-item>
          <el-button @click="handleRest">重置</el-button>
          <el-button type="primary" v-loading="loading" @click="handleSubmit">{{submitBtnTit}}</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>
<script lang="ts">
import { defineComponent, ref, getCurrentInstance, computed } from 'vue'
import { getTree, formateTree, recursionMachine } from '@/utils'
import { HTTP_CONFIG } from '@/config/http'
import _ from 'lodash'
import EditableTree, { TreeProps, TreeDataKeyMapProps, ROOT_NODE_PID } from '@/components/EditableTree.vue'
import systemConfigCategoryRules from '@/validator/systemConfigCategory'
import {
  systemConfigCategoryListApi,
  SystemConfigCategoryProps,
  addSystemConfigCategoryApi,
  SystemConfigCategoryDetailProps,
  systemConfigCategoryDetailApi,
  EditSystemConfigCategoryProps,
  editSystemConfigCategoryApi,
  DeleteSystemConfigCategoryProps,
  deleteSystemConfigCategoryApi
} from '@/api/systemConfigCategory'
export default defineComponent({
  components: {
    EditableTree
  },
  emits: [
    'edit-category',
    'add-category',
    'select-row'
  ],
  props: {
    mode: {
      type: String,
      default: 'add'
    }
  },
  setup (props, { emit }) {
    const { proxy } = (getCurrentInstance() as any)
    const editableTreeRef = ref()
    const treeIsLoading = ref(false)

    // 分类数据列表
    const treeData = ref<TreeProps[]>([])
    const treeDataKeyMap: TreeDataKeyMapProps = { id: 'id', label: 'name', pid: 'pid', children: 'children' }
    const getSystemConfigCategoryList = async () => {
      treeIsLoading.value = true
      const { status, data, message } = await systemConfigCategoryListApi()
      if (status === HTTP_CONFIG.API_SUCCESS_CODE) {
        treeData.value = []
        const treeArr: TreeProps[] = []
        getTree(treeArr, data)
        const allTreeNodeData = [{ id: 0, pid: ROOT_NODE_PID, name: '全部配置分类', children: treeArr || [] }]
        formateTree((treeData.value as any), allTreeNodeData, (treeDataKeyMap as any))
      } else {
        proxy.message({
          showClose: true,
          message,
          type: 'error',
          duration: 3000
        })
      }
      treeIsLoading.value = false
    }
    getSystemConfigCategoryList()

    // 添加、编辑分类
    const dialogFormVisible = ref(false)
    const hdTitle = computed(() => {
      return props.mode === 'edit' ? '编辑分类' : '添加分类'
    })
    // 表单
    const tbIsLoading = ref(false)
    const loading = ref(false)
    const addFormRef = ref()
    const formMdl = ref<any>({
      id: '',
      categoryName: '',
      parentCategory: [],
      sort: ''
    })
    const categoryListTree = computed(() => {
      const tempSystemConfigCategoryTree = _.cloneDeep(treeData.value)
      recursionMachine(tempSystemConfigCategoryTree, (dataItem: any) => {
        if (dataItem.id === formMdl.value.id) {
          dataItem.disabled = true
        }
      })
      tempSystemConfigCategoryTree[0].label = '顶级分类'
      return tempSystemConfigCategoryTree
    })
    const submitBtnTit = computed(() => {
      return props.mode === 'edit' ? '更新' : '添加'
    })

    const handleOpenAddDialog = () => {
      emit('add-category')
      dialogFormVisible.value = true
    }
    const handleOpenEditDialog = ({ data }: {data: TreeProps}) => {
      emit('edit-category')
      formMdl.value.parentCategory = data.id
      dialogFormVisible.value = true
      // 获取详情
      categoryDetail(data.id)
    }

    // 重置
    const handleRest = () => {
      resetForm()
    }
    const resetForm = () => {
      addFormRef.value.resetFields()
      formMdl.value.parentCategory = ''
    }

    // 提交
    const handleSubmit = async () => {
      const validateStatus = await new Promise((resolve) => {
        addFormRef.value.validate((valid: any) => {
          if (valid) {
            resolve(true)
          } else {
            resolve(false)
            return false
          }
        })
      })

      if (!validateStatus || loading.value) {
        return false
      }
      const params: {[key: string]: any} = {
        name: formMdl.value.categoryName,
        pid: formMdl.value.parentCategory,
        sort: formMdl.value.sort
      }
      if (props.mode === 'add') {
        loading.value = true
        await addSystemConfigCategory((params as any))
        getSystemConfigCategoryList()
        loading.value = false
      } else if (props.mode === 'edit') {
        params.id = formMdl.value.id
        await editSystemConfigCategory((params as any))
        getSystemConfigCategoryList()
      }
    }

    // 添加
    const addSystemConfigCategory = async (data: SystemConfigCategoryProps) => {
      const { status, message } = await addSystemConfigCategoryApi(data)
      if (status === HTTP_CONFIG.API_SUCCESS_CODE) {
        resetForm()
        proxy.message.success({
          message,
          duration: 3000
        })
      } else {
        proxy.message.warning({
          message,
          duration: 3000
        })
      }
    }

    // 编辑
    const editSystemConfigCategory = async (data: EditSystemConfigCategoryProps) => {
      const { status, message } = await editSystemConfigCategoryApi(data)
      if (status === HTTP_CONFIG.API_SUCCESS_CODE) {
        proxy.message.success({
          message,
          duration: 3000
        })
      } else {
        proxy.message.warning({
          message,
          duration: 3000
        })
      }
    }

    // 详情
    const categoryDetail = async (id: string) => {
      const params: SystemConfigCategoryDetailProps = {
        id
      }
      const { status, data, message } = await systemConfigCategoryDetailApi(params)
      if (status === HTTP_CONFIG.API_SUCCESS_CODE) {
        formMdl.value = {
          id: data.id,
          categoryName: data.name,
          parentCategory: data.pid,
          sort: data.sort
        }
      } else {
        proxy.message.warning({
          message,
          duration: 3000
        })
      }
    }

    // 删除
    const handleDeleteSystemConfigCategory = ({ data }: {data: TreeProps}) => {
      deleteSystemConfigCategory(data.id)
    }

    // 选择
    const handleSelectRow = (data: TreeProps) => {
      emit('select-row', data)
    }

    const deleteSystemConfigCategory = async (id: string) => {
      const params: DeleteSystemConfigCategoryProps = {
        id
      }
      const { status, message } = await deleteSystemConfigCategoryApi(params)
      if (status === HTTP_CONFIG.API_SUCCESS_CODE) {
        getSystemConfigCategoryList()
      } else {
        proxy.message.warning({
          message,
          duration: 3000
        })
      }
    }

    // 关闭Dialog
    const handleClose = () => {
      resetForm()
      formMdl.value.id = ''
    }

    return {
      editableTreeRef,
      treeData,
      treeIsLoading,
      dialogFormVisible,
      hdTitle,
      formRules: systemConfigCategoryRules,
      tbIsLoading,
      loading,
      addFormRef,
      formMdl,
      categoryListTree,
      submitBtnTit,
      handleOpenAddDialog,
      handleOpenEditDialog,
      handleRest,
      handleSubmit,
      handleDeleteSystemConfigCategory,
      handleSelectRow,
      handleClose
    }
  }
})
</script>
<style lang="scss" scoped>
.category-editable-tree {
  padding: 10px 15px;
}
</style>
