/* 商品分类 Validator */
import {
  isRealNumber
} from '@/utils/reg'
const goodsCategoryRules = (() => {
  const validatorName = (rule: any, value: any, callback: any) => {
    if (value === '' || !value) {
      callback(new Error('请输入商品分类名称'))
    }
    callback()
  }

  const validatorParentCategory = (rule: any, value: any, callback: any) => {
    if (value === '') {
      callback(new Error('请选择所属父级分类'))
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
    categoryName: [
      { required: true, validator: validatorName, trigger: 'blur' }
    ],
    parentCategory: [
      { required: true, validator: validatorParentCategory, trigger: 'blur' }
    ],
    sort: [
      { validator: validatorSort, trigger: 'blur' }
    ]
  }
})()

export default goodsCategoryRules
