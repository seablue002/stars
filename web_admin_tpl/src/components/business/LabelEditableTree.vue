<template>
  <div class="label-editable-tree">
    <EditableTree
      ref="editableTreeRef"
      v-loading="treeIsLoading"
      :treeData="treeData"
      :styleConf="{ width: '500px' }"
      :mode="mode"
      @add-category="handleOpenAddDialog"
      @edit-category="handleOpenEditDialog"
      @delete-category="handleDeleteLabel"
      @select-row="handleSelectRow">
    </EditableTree>
    <el-dialog
      v-if="dialogFormVisible"
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
        label-width="120px">
        <el-tabs v-model.trim="activeTabName">
          <el-tab-pane label="基本信息" name="base">
            <el-form-item prop="name" label="标签名称">
              <el-input v-model.trim="formMdl.name" clearable placeholder="请输入标签名称"></el-input>
            </el-form-item>
            <el-form-item prop="pid" label="上级标签">
              <el-cascader v-model="formMdl.pid" :options="labelListTree" :props="{ checkStrictly: true, value: 'id' }" filterable clearable placeholder="请选择上级标签" />
            </el-form-item>
            <el-form-item prop="sort" label="排序" class="small-input">
              <el-input v-model="formMdl.sort" clearable placeholder="请输入排序"></el-input>
            </el-form-item>
          </el-tab-pane>
        </el-tabs>
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
import labelRules from '@/validator/label'
import {
  labelListApi,
  LabelProps,
  addLabelApi,
  LabelDetailProps,
  labelDetailApi,
  EditLabelProps,
  editLabelApi,
  DeleteLabelProps,
  deleteLabelApi
} from '@/api/label'
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

    // 标签数据列表
    const treeData = ref<TreeProps[]>([])
    const treeDataKeyMap: TreeDataKeyMapProps = { id: 'id', label: 'name', pid: 'pid', children: 'children', pids: 'pids' }
    const getLabelList = async () => {
      treeIsLoading.value = true
      const { status, data, message } = await labelListApi()
      if (status === HTTP_CONFIG.API_SUCCESS_CODE) {
        treeData.value = []
        const treeArr: TreeProps[] = []
        getTree(treeArr, data)
        const allTreeNodeData = [{ id: 0, pid: ROOT_NODE_PID, name: '全部标签', children: treeArr || [], pids: '' }]

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
    getLabelList()

    // 添加、编辑标签
    const dialogFormVisible = ref(false)
    const hdTitle = computed(() => {
      return props.mode === 'edit' ? '编辑标签' : '添加标签'
    })
    // tab
    const activeTabName = ref('base')
    // 表单
    const tbIsLoading = ref(false)
    const loading = ref(false)
    const addFormRef = ref()
    const formMdl = ref<any>({
      id: '',
      name: '',
      pid: [],
      sort: ''
    })
    const labelListTree = computed(() => {
      const tempLabelTree = _.cloneDeep(treeData.value)
      recursionMachine(tempLabelTree, (dataItem: any) => {
        if (dataItem.id === formMdl.value.id) {
          dataItem.disabled = true
        }
      })
      tempLabelTree[0].label = '顶级标签'
      return tempLabelTree
    })
    const submitBtnTit = computed(() => {
      return props.mode === 'edit' ? '更新' : '添加'
    })

    const handleOpenAddDialog = ({ data }: {data: TreeProps}) => {
      emit('add-category')
      let pids = data?.pids?.split(',')
      pids = pids?.map((pid) => {
        return Number(pid)
      }) as any
      pids && pids.push(data.id)
      formMdl.value.pid = pids
      dialogFormVisible.value = true
    }
    const handleOpenEditDialog = async ({ data }: {data: TreeProps}) => {
      emit('edit-category')
      let pids = data?.pids?.split(',')
      pids = pids?.map((pid) => {
        return Number(pid)
      }) as any
      pids && pids.push(data.id)
      formMdl.value.pid = pids
      dialogFormVisible.value = true
      // 获取详情
      await labelDetail(data.id)
    }

    // 重置
    const handleRest = () => {
      resetForm()
    }
    const resetForm = async () => {
      addFormRef.value.resetFields()
      formMdl.value.pid = ''
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
        name: formMdl.value.name,
        pid: formMdl.value.pid,
        sort: formMdl.value.sort
      }
      const formData = new FormData()
      for (const attr in params) {
        formData.append(attr, params[attr])
      }
      if (props.mode === 'add') {
        loading.value = true
        await addLabel((formData as any))
        getLabelList()
        loading.value = false
      } else if (props.mode === 'edit') {
        formData.append('id', formMdl.value.id)
        await editLabel((formData as any))
        getLabelList()
      }
    }

    // 添加
    const addLabel = async (data: LabelProps) => {
      const { status, message } = await addLabelApi(data)
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
    const editLabel = async (data: EditLabelProps) => {
      const { status, message } = await editLabelApi(data)
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
    const labelDetail = async (id: string) => {
      const params: LabelDetailProps = {
        id
      }
      const { status, data, message } = await labelDetailApi(params)
      if (status === HTTP_CONFIG.API_SUCCESS_CODE) {
        formMdl.value = {
          ...data,
          id: data.id,
          name: data.name,
          pid: data.pid,
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
    const handleDeleteLabel = ({ data }: {data: TreeProps}) => {
      deleteLabel(data.id)
    }

    // 选择
    const handleSelectRow = (data: TreeProps) => {
      emit('select-row', data)
    }

    const deleteLabel = async (id: string) => {
      const params: DeleteLabelProps = {
        id
      }
      const { status, message } = await deleteLabelApi(params)
      if (status === HTTP_CONFIG.API_SUCCESS_CODE) {
        getLabelList()
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
      formRules: labelRules,
      tbIsLoading,
      activeTabName,
      loading,
      addFormRef,
      formMdl,
      labelListTree,
      submitBtnTit,
      handleOpenAddDialog,
      handleOpenEditDialog,
      handleRest,
      handleSubmit,
      handleDeleteLabel,
      handleSelectRow,
      handleClose
    }
  }
})
</script>
<style lang="scss" scoped>
.label-editable-tree {
  padding: 10px 15px;

  .line {
    text-align: center;
  }
}
</style>
