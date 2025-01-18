/* 栏目自定义字段配置 Validator */
import {
  isRealNumber
} from '@/utils/reg'
const columnExtendFieldsConfigRules = (() => {
  const validatorId = (rule: any, value: any, callback: any) => {
    if (value === '') {
      callback(new Error('id必须'))
    }
    callback()
  }
  const validatorField = (rule: any, value: any, callback: any) => {
    if (value === '' || !value) {
      callback(new Error('请输入栏目自定义字段配置字段名称'))
    }
    callback()
  }
  const validatorFieldType = (rule: any, value: any, callback: any) => {
    if (value === '' || !value) {
      callback(new Error('请选择栏目自定义字段类型名称'))
    }
    callback()
  }
  const validatorFieldLength = (rule: any, value: any, callback: any) => {
    if (value === '' || !value) {
      callback(new Error('请输入栏目自定义字段类型长度名称'))
    }
    callback()
  }
  const validatorLabel = (rule: any, value: any, callback: any) => {
    if (value === '' || !value) {
      callback(new Error('请输入栏目自定义字段配置名称'))
    }
    callback()
  }
  const validatorType = (rule: any, value: any, callback: any) => {
    if (value === '' || !value) {
      callback(new Error('请选择栏目自定义字段配置表单元素类型'))
    }
    callback()
  }
  const validatorSort = (rule: any, value: any, callback: any) => {
    if (value !== '') {
      if (!isRealNumber.test(value)) {
        callback(new Error('排序必须为数字'))
      }
    }
    callback()
  }

  return {
    id: [
      { required: true, validator: validatorId, trigger: 'blur' }
    ],
    field: [
      { required: true, validator: validatorField, trigger: 'blur' }
    ],
    field_type: [
      { required: true, validator: validatorFieldType, trigger: 'blur' }
    ],
    field_length: [
      { required: true, validator: validatorFieldLength, trigger: 'blur' }
    ],
    label: [
      { required: true, validator: validatorLabel, trigger: 'blur' }
    ],
    type: [
      { required: true, validator: validatorType, trigger: 'blur' }
    ],
    sort: [
      { validator: validatorSort, trigger: 'blur' }
    ]
  }
})()

export default columnExtendFieldsConfigRules
