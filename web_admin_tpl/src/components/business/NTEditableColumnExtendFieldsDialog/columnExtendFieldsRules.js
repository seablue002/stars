/* Validator */
import { realNumberReg } from '@/utils/helper/regExp'

const columnExtendFieldsRules = (() => {
  const validatorId = (rule, value, callback) => {
    if (value === '') {
      callback(new Error('id必须'))
    }
    callback()
  }
  const validatorField = (rule, value, callback) => {
    if (value === '' || !value) {
      callback(new Error('请输入字段名称'))
    }
    callback()
  }
  const validatorFieldType = (rule, value, callback) => {
    if (value === '' || !value) {
      callback(new Error('请选择字段类型'))
    }
    callback()
  }
  const validatorFieldLength = (rule, value, callback) => {
    if (value === '' || !value) {
      callback(new Error('请输入字段长度'))
    }
    callback()
  }
  const validatorLabel = (rule, value, callback) => {
    if (value === '' || !value) {
      callback(new Error('请输入abel名称'))
    }
    callback()
  }
  const validatorSort = (rule, value, callback) => {
    if (value !== '') {
      if (!realNumberReg.test(value)) {
        callback(new Error('排序必须为数字'))
      }
    }
    callback()
  }

  return {
    id: [{ required: true, validator: validatorId, trigger: 'blur' }],
    field: [{ required: true, validator: validatorField, trigger: 'blur' }],
    field_type: [
      { required: true, validator: validatorFieldType, trigger: 'blur' },
    ],
    field_length: [
      { required: true, validator: validatorFieldLength, trigger: 'blur' },
    ],
    name: [{ required: true, validator: validatorLabel, trigger: 'blur' }],
    props: [{ required: true, message: '请输入元素属性', trigger: 'blur' }],
    sort: [{ validator: validatorSort, trigger: 'blur' }],
  }
})()

export default columnExtendFieldsRules
