<template>
  <div class="nt-editable-column-extend-fields-dialog">
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
        :rules="columnExtendFieldsRules"
        :model="formMdl"
        label-width="130px"
      >
        <el-form-item prop="field" label="字段名称">
          <el-input
            v-model="formMdl.field"
            :readonly="isDetailMode"
            clearable
            placeholder="请输入字段名称"
            class="w-4/5"
          ></el-input>
        </el-form-item>
        <el-form-item prop="field_type" label="字段类型">
          <el-select
            v-model="formMdl.field_type"
            :disabled="isDetailMode"
            clearable
            placeholder="请选择字段类型"
            class="w-4/5"
          >
            <el-option
              v-for="item in FIELD_TYPE_LIST"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </el-form-item>
        <el-form-item prop="field_length" label="字段长度">
          <el-input
            v-model="formMdl.field_length"
            :readonly="isDetailMode"
            clearable
            placeholder="请输入字段长度"
            class="w-4/5"
          ></el-input>
        </el-form-item>

        <el-form-item prop="name" label="label名称">
          <el-input
            v-model="formMdl.name"
            :readonly="isDetailMode"
            clearable
            placeholder="请输入label名称"
            class="w-4/5"
          ></el-input>
        </el-form-item>

        <el-form-item prop="props" label="元素属性">
          <JsonEditorVue
            v-model="formMdl.props"
            class="w-4/5 !max-w-full"
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
import { defineComponent, ref, computed, nextTick } from 'vue'
import { API_HOST } from '@/settings/config/http'
import JsonEditorVue from 'json-editor-vue3'
import useCurrentInstance from '@/hooks/business/useCurrentInstance'
import columnExtendFieldsRules from './columnExtendFieldsRules'

export default defineComponent({
  name: 'NTEditableColumnExtendFieldsDialog',
  components: {
    JsonEditorVue,
  },
  emits: ['add-success', 'edit-success', 'delete-success'],
  setup(props, { emit }) {
    const { $api, $apiCode, $message } = useCurrentInstance()

    const FIELD_TYPE_LIST = [
      {
        label: '字符型0-255字节(VARCHAR)',
        value: 'VARCHAR',
      },
      {
        label: '小型字符型(TEXT)',
        value: 'TEXT',
      },
      {
        label: '中型字符型(MEDIUMTEXT)',
        value: 'MEDIUMTEXT',
      },
      {
        label: '大型字符型(LONGTEXT)',
        value: 'LONGTEXT',
      },
      {
        label: '小数值型(TINYINT)',
        value: 'TINYINT',
      },
      {
        label: '中数值型(SMALLINT)',
        value: 'SMALLINT',
      },
      {
        label: '大数值型(INT)',
        value: 'INT',
      },
      {
        label: '超大数值型(BIGINT)',
        value: 'BIGINT',
      },
      {
        label: '数值浮点型(FLOAT)',
        value: 'FLOAT',
      },
      {
        label: '数值双精度型(DOUBLE)',
        value: 'DOUBLE',
      },
    ]

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
        title = '栏目自定义字段详情'
      } else if (isCreateMode.value) {
        title = '添加栏目自定义字段'
      } else if (isEditMode.value) {
        title = '编辑栏目自定义字段'
      }
      return title
    })

    // 表单
    const loading = ref(false)
    const addFormRef = ref()
    const formMdl = ref({
      id: '',
      field: '',
      field_type: '',
      field_length: '',
      name: '',
      props: {},
      sort: 0,
      status: 1,
    })

    const submitBtnTit = computed(() => {
      return dialogMode.value === 'edit' ? '提交' : '添加'
    })

    // 添加
    const addColumnExtendFields = async (data) => {
      const apiRes =
        await $api.columnExtendFieldsConfig.addColumnExtendFieldsConfigApi(data)
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
    const editColumnExtendFields = async (data) => {
      const apiRes =
        await $api.columnExtendFieldsConfig.editColumnExtendFieldsConfigApi(
          data
        )
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
    const columnExtendFieldsDetail = async (id) => {
      const params = {
        id,
      }
      const apiRes =
        await $api.columnExtendFieldsConfig.columnExtendFieldsConfigDetailApi(
          params
        )
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
          await columnExtendFieldsDetail(formMdl.value.id)
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
        field: formMdl.value.field,
        field_type: formMdl.value.field_type,
        field_length: formMdl.value.field_length,
        name: formMdl.value.name,
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
        await addColumnExtendFields(formData)
        emit('add-success')
        loading.value = false
      } else if (dialogMode.value === 'edit') {
        formData.append('id', formMdl.value.id)
        await editColumnExtendFields(formData)
        emit('edit-success')
      }
    }

    return {
      FIELD_TYPE_LIST,
      API_HOST,
      dialogFormVisible,
      isDetailMode,
      isCreateMode,
      isEditMode,
      hdTitle,
      columnExtendFieldsRules,
      loading,
      addFormRef,
      formMdl,
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
