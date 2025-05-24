<template>
  <div class="nt-editable-system-config-dialog">
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
        :rules="isDetailMode ? null : systemConfigRules"
        :model="formMdl"
        label-width="130px"
      >
        <el-form-item prop="name" label="配置名称">
          <el-input
            v-model="formMdl.name"
            :readonly="isDetailMode"
            clearable
            placeholder="请输入配置名称"
            class="w-4/5"
          ></el-input>
        </el-form-item>

        <el-form-item prop="cid" label="所属分类">
          <el-cascader
            v-model="formMdl.cid"
            :options="treeData"
            :props="{ checkStrictly: true, value: 'id' }"
            filterable
            :disabled="isDetailMode"
            clearable
            placeholder="请选择配置所属分类"
            class="w-4/5"
          />
        </el-form-item>

        <el-form-item prop="field" label="字段名称">
          <el-input
            v-model="formMdl.field"
            :readonly="isDetailMode"
            clearable
            placeholder="请输入字段名称"
            class="w-4/5"
          ></el-input>
        </el-form-item>

        <el-form-item prop="props" label="元素属性">
          <JsonEditorVue
            v-model="formMdl.props"
            class="w-4/5 !max-w-full h-[300px]"
          ></JsonEditorVue>
        </el-form-item>

        <el-form-item prop="sort">
          <template #label>
            排序
            <el-popover placement="top-start" width="auto" trigger="hover">
              <template #default>
                <p class="ml-[12px]">数值越小，越靠前</p>
              </template>
              <template #reference>
                <i class="ri-question-line ml-[4px]"></i>
              </template>
            </el-popover>
          </template>
          <el-input
            v-model="formMdl.sort"
            :readonly="isDetailMode"
            clearable
            placeholder="请输入排序"
            class="w-4/5"
          ></el-input>
        </el-form-item>

        <el-form-item prop="status" label="状态">
          <el-switch
            v-model="formMdl.status"
            :disabled="isDetailMode"
            active-text="正常"
            inactive-text="停用"
            :active-value="1"
            :inactive-value="0"
          ></el-switch>
        </el-form-item>
        <el-form-item v-if="isCreateMode || isEditMode">
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
import { defineComponent, ref, computed, nextTick, onMounted } from 'vue'
import JsonEditorVue from 'json-editor-vue3'
import { ROOT_NODE_PID } from '@/components/NTEditableTree/index.vue'
import useCurrentInstance from '@/hooks/business/useCurrentInstance'
import systemConfigRules from './systemConfigRules'

export default defineComponent({
  name: 'NTEditableSystemConfigDialog',
  components: {
    JsonEditorVue,
  },
  emits: ['add-success', 'edit-success', 'delete-success'],
  setup(props, { emit }) {
    const { $api, $apiCode, $message, $tree } = useCurrentInstance()
    const { getTree, formateTree } = $tree

    // 添加、编辑
    const dialogMode = ref('add')

    // 是否是详情模式
    const isDetailMode = computed(() => {
      return ['detail'].includes(dialogMode.value)
    })

    // 是否新增模式
    const isCreateMode = computed(() => {
      return ['add'].includes(dialogMode.value)
    })

    // 是否编辑模式
    const isEditMode = computed(() => {
      return ['edit'].includes(dialogMode.value)
    })

    const dialogFormVisible = ref(false)

    const hdTitle = computed(() => {
      let title = ''
      if (isDetailMode.value) {
        title = '系统配置项详情'
      } else if (isCreateMode.value) {
        title = '添加系统配置项'
      } else if (isEditMode.value) {
        title = '编辑系统配置项'
      }
      return title
    })

    // 表单
    const loading = ref(false)
    const addFormRef = ref()
    const formMdl = ref({
      id: '',
      name: '',
      cid: '',
      field: '',
      props: {},
      sort: 0,
      status: 1,
    })

    const submitBtnTit = computed(() => {
      return dialogMode.value === 'edit' ? '提交' : '添加'
    })

    // 添加
    const addSystemConfig = async (data) => {
      const apiRes = await $api.systemConfig.addSystemConfigApi(data)
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
    const editSystemConfig = async (data) => {
      const apiRes = await $api.systemConfig.editSystemConfigApi(data)
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
    const systemConfigDetail = async (id) => {
      const params = {
        id,
      }
      const apiRes = await $api.systemConfig.systemConfigDetailApi(params)
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

    // 打开添加、编辑Dialog
    const open = async (data) => {
      const { mode } = data
      dialogMode.value = mode
      dialogFormVisible.value = true

      nextTick(async () => {
        if (['edit', 'detail'].includes(mode)) {
          formMdl.value.id = data.id
          await systemConfigDetail(formMdl.value.id)
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
        cid: formMdl.value.cid,
        field: formMdl.value.field,
        props: JSON.stringify(formMdl.value.props),
        sort: formMdl.value.sort,
        status: formMdl.value.status,
      }
      const formData = new FormData()
      Object.entries(params).forEach(([attr, value]) => {
        formData.append(attr, value)
      })

      if (dialogMode.value === 'add') {
        loading.value = true
        await addSystemConfig(formData)
        emit('add-success')
        loading.value = false
      } else if (dialogMode.value === 'edit') {
        formData.append('id', formMdl.value.id)
        await editSystemConfig(formData)
        emit('edit-success')
      }
    }

    // 分类数据列表
    const treeData = ref([])
    const getSystemConfigCategoryList = async () => {
      const apiRes =
        await $api.systemConfigCategory.systemConfigCategoryListApi()
      const { status, data, message } = apiRes.data
      if (status === $apiCode.SUCCESS) {
        treeData.value = []
        const treeArr = []
        getTree(treeArr, data, {
          id: 'id',
          name: 'name',
          pid: 'pid',
        })
        const allTreeNodeData = [
          {
            id: 0,
            pid: ROOT_NODE_PID,
            name: '顶级分类',
            children: treeArr || [],
          },
        ]
        formateTree(treeData.value, allTreeNodeData, {
          id: 'id',
          label: 'name',
          pid: 'pid',
          children: 'children',
        })
      } else {
        $message.message({
          showClose: true,
          message,
          type: 'error',
          duration: 3000,
        })
      }
    }
    onMounted(() => {
      getSystemConfigCategoryList()
    })

    return {
      dialogFormVisible,
      isDetailMode,
      isCreateMode,
      isEditMode,
      hdTitle,
      systemConfigRules,
      loading,
      addFormRef,
      formMdl,
      submitBtnTit,
      handleRest,
      handleSubmit,
      open,
      close,
      handleClose,
      treeData,
    }
  },
})
</script>
<style lang="scss" scoped></style>
