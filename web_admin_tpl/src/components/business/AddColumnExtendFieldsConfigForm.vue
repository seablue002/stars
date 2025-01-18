<template>
  <div class="add-system-config-form">
    <el-row>
      <el-col :span="15">
        <el-form
          ref="formRef"
          :rules="!isReadOnly ? formRules : null"
          :model="formMdl"
          v-loading="tbIsLoading"
          label-width="120px">
          <el-form-item prop="type" label="字段输入类型">
            <el-select v-model="formMdl.type" :readonly="isReadOnly" clearable placeholder="请选择字段输入类型">
              <el-option-group
                v-for="group in FORM_ELE_TYPE_LIST"
                :key="group.label"
                :label="group.label"
              >
                <el-option
                  v-for="item in group.options"
                  :key="item.value"
                  :label="item.label"
                  :value="item.value"
                />
              </el-option-group>
            </el-select>
          </el-form-item>
          <el-form-item prop="field" label="字段名称">
            <el-input v-model="formMdl.field" :readonly="isReadOnly" clearable placeholder="请输入字段名称"></el-input>
          </el-form-item>
          <el-form-item prop="field_type" label="字段类型">
            <el-select v-model="formMdl.field_type" :readonly="isReadOnly" clearable placeholder="请选择字段类型">
              <el-option
                v-for="item in fieldTypeList"
                :key="item.value"
                :label="item.label"
                :value="item.value"
              />
            </el-select>
          </el-form-item>
          <el-form-item prop="field_length" label="字段长度">
            <el-input v-model="formMdl.field_length" :readonly="isReadOnly" clearable placeholder="请输入字段长度"></el-input>
          </el-form-item>
          <el-form-item prop="label" label="label名称">
            <el-input v-model="formMdl.label" :readonly="isReadOnly" clearable placeholder="请输入配置label名称"></el-input>
          </el-form-item>
          <el-form-item prop="tips" label="配置介绍">
            <el-input v-model="formMdl.tips" :readonly="isReadOnly" clearable placeholder="请输入配置介绍"></el-input>
          </el-form-item>
          <el-form-item prop="props" label="元素属性">
            <el-input
            v-model="formMdl.props"
            type="textarea"
            :rows="6"
            :readonly="isReadOnly"
            clearable placeholder="请输入元素属性props">
            </el-input>
          </el-form-item>
          <el-form-item prop="options" label="选项值">
            <el-input v-model="formMdl.options" type="textarea" :rows="6" :readonly="isReadOnly" clearable placeholder="请输入选项值options"></el-input>
          </el-form-item>
          <el-form-item prop="value" label="默认值">
            <el-input v-model="formMdl.value" :readonly="isReadOnly" clearable placeholder="请输入默认值"></el-input>
          </el-form-item>
          <el-form-item prop="validate_rules" label="校验规则">
            <el-input v-model="formMdl.validate_rules" type="textarea" :rows="6" :readonly="isReadOnly" clearable placeholder="请输入校验规则"></el-input>
          </el-form-item>
          <el-form-item prop="sort" label="排序">
            <el-input v-model="formMdl.sort" :readonly="isReadOnly" clearable placeholder="请输入排序"></el-input>
            <Tips title="数值越小越靠前"></Tips>
          </el-form-item>
          <el-form-item prop="status" label="状态">
            <el-switch v-model="formMdl.status" :disabled="isReadOnly" active-text="正常" inactive-text="停用" :active-value="1" :inactive-value="0"></el-switch>
          </el-form-item>
          <el-form-item class="form-footer-opt-btns" v-if="['add', 'edit'].includes(mode)">
            <el-button @click="resetForm">重置</el-button>
            <el-button type="primary" :loading="loading" @click="handleSubmit">{{ submitBtnTit }}</el-button>
          </el-form-item>
        </el-form>
      </el-col>
    </el-row>
  </div>
</template>
<script lang="ts">
import { defineComponent, ref, getCurrentInstance, onMounted, nextTick, computed } from 'vue'
import columnExtendFieldsConfigRules from '@/validator/columnExtendFieldsConfig'
import { HTTP_CONFIG } from '@/config/http'
import { FORM_ELE_TYPE_LIST } from '@/config/constant'
import {
  ColumnExtendFieldsConfigProps,
  addColumnExtendFieldsConfigApi,
  ColumnExtendFieldsConfigDetailProps,
  columnExtendFieldsConfigDetailApi,
  EditColumnExtendFieldsConfigProps,
  editColumnExtendFieldsConfigApi
} from '@/api/columnExtendFieldsConfig'
export default defineComponent({
  props: {
    mode: {
      type: String,
      default: 'add'
    }
  },
  emits: [
    'add-success',
    'edit-success'
  ],
  setup (props, { emit }) {
    const { proxy } = (getCurrentInstance() as any)
    // 表单
    const formRef = ref()
    const tbIsLoading = ref(false)
    const loading = ref(false)
    const submitBtnTit = computed(() => {
      return props.mode === 'edit' ? '更新' : '添加'
    })
    const isReadOnly = computed(() => {
      return ['detail'].includes(props.mode)
    })

    const fieldTypeList = [
      {
        label: '字符型0-255字节(VARCHAR)',
        value: 'VARCHAR'
      },
      {
        label: '小型字符型(TEXT)',
        value: 'TEXT'
      },
      {
        label: '中型字符型(MEDIUMTEXT)',
        value: 'MEDIUMTEXT'
      },
      {
        label: '大型字符型(LONGTEXT)',
        value: 'LONGTEXT'
      },
      {
        label: '小数值型(TINYINT)',
        value: 'TINYINT'
      },
      {
        label: '中数值型(SMALLINT)',
        value: 'SMALLINT'
      },
      {
        label: '大数值型(INT)',
        value: 'INT'
      },
      {
        label: '超大数值型(BIGINT)',
        value: 'BIGINT'
      },
      {
        label: '数值浮点型(FLOAT)',
        value: 'FLOAT'
      },
      {
        label: '数值双精度型(DOUBLE)',
        value: 'DOUBLE'
      }
    ]

    const formMdl = ref<ColumnExtendFieldsConfigProps>({
      id: '',
      field: '',
      field_type: '',
      field_length: '',
      label: '',
      tips: '',
      type: '',
      props: '',
      options: '',
      value: '',
      validate_rules: '',
      sort: 0,
      status: 1
    })

    const handleSubmit = async () => {
      const validateStatus = await new Promise((resolve) => {
        formRef.value.validate((valid: any) => {
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

      const data: ColumnExtendFieldsConfigProps = {
        field: formMdl.value.field,
        field_type: formMdl.value.field_type,
        field_length: formMdl.value.field_length,
        label: formMdl.value.label,
        tips: formMdl.value.tips,
        type: formMdl.value.type,
        props: formMdl.value.props,
        options: formMdl.value.options,
        value: formMdl.value.value,
        validate_rules: formMdl.value.validate_rules,
        sort: formMdl.value.sort,
        status: formMdl.value.status
      }
      if (props.mode === 'add') {
        loading.value = true
        await addColumnExtendFieldsConfig(data)
        loading.value = false
      } else if (props.mode === 'edit') {
        data.id = formMdl.value.id
        loading.value = true
        await editColumnExtendFieldsConfig((data as EditColumnExtendFieldsConfigProps))
        loading.value = false
      }
    }

    // 添加
    const addColumnExtendFieldsConfig = async (data: ColumnExtendFieldsConfigProps) => {
      const { status, message } = await addColumnExtendFieldsConfigApi(data)
      if (status === HTTP_CONFIG.API_SUCCESS_CODE) {
        resetForm()
        emit('add-success')
        proxy.message.success({
          message: '添加成功',
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
    const editColumnExtendFieldsConfig = async (data: EditColumnExtendFieldsConfigProps) => {
      const { status, message } = await editColumnExtendFieldsConfigApi(data)
      if (status === HTTP_CONFIG.API_SUCCESS_CODE) {
        emit('edit-success')
        proxy.message.success({
          message: '编辑成功',
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
    const getDetail = async (id: any) => {
      const params: ColumnExtendFieldsConfigDetailProps = {
        id
      }
      tbIsLoading.value = true
      const { status, data, message } = await columnExtendFieldsConfigDetailApi(params)
      if (status === HTTP_CONFIG.API_SUCCESS_CODE) {
        formMdl.value = { ...data }
      } else {
        proxy.message.warning({
          message,
          duration: 3000
        })
      }
      tbIsLoading.value = false
    }
    onMounted(() => {
      nextTick(() => {
        if (formMdl.value.id && ['edit', 'detail'].includes(props.mode)) {
          getDetail(formMdl.value.id)
        }
      })
    })

    // 重置
    const resetForm = () => {
      formRef.value.resetFields()
    }

    return {
      formRules: columnExtendFieldsConfigRules,
      formRef,
      tbIsLoading,
      loading,
      submitBtnTit,
      isReadOnly,
      formMdl,
      fieldTypeList,
      handleSubmit,
      resetForm,
      getDetail,
      FORM_ELE_TYPE_LIST
    }
  }
})
</script>
<style lang="scss" scoped>
</style>
