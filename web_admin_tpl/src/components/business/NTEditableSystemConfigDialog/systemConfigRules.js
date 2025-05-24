/* Validator */
import { realNumberReg } from '@/utils/helper/regExp'

const rules = (() => {
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
  const validatorName = (rule, value, callback) => {
    if (value === '' || !value) {
      callback(new Error('请输入配置名称'))
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
    cid: [{ required: true, message: '请选择所属分类', trigger: 'blur' }],
    name: [{ required: true, validator: validatorName, trigger: 'blur' }],
    field: [{ required: true, validator: validatorField, trigger: 'blur' }],
    props: [{ required: true, message: '请输入元素属性', trigger: 'blur' }],
    sort: [{ validator: validatorSort, trigger: 'blur' }],
  }
})()

export default rules
