/* 系统配置 Validator */
import {
  isRealNumber
} from '@/utils/reg'
const systemConfigRules = (() => {
  const validatorId = (rule: any, value: any, callback: any) => {
    if (value === '') {
      callback(new Error('id必须'))
    }
    callback()
  }
  const validatorCid = (rule: any, value: any, callback: any) => {
    if (value === '') {
      callback(new Error('分类id必须'))
    }
    callback()
  }
  const validatorField = (rule: any, value: any, callback: any) => {
    if (value === '' || !value) {
      callback(new Error('请输入系统配置字段名称'))
    }
    callback()
  }
  const validatorLabel = (rule: any, value: any, callback: any) => {
    if (value === '' || !value) {
      callback(new Error('请输入系统配置名称'))
    }
    callback()
  }
  const validatorType = (rule: any, value: any, callback: any) => {
    if (value === '' || !value) {
      callback(new Error('请选择系统配置表单元素类型'))
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
    cid: [
      { required: true, validator: validatorCid, trigger: 'blur' }
    ],
    field: [
      { required: true, validator: validatorField, trigger: 'blur' }
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

export default systemConfigRules
