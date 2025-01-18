/* 轮播图 Validator */
import {
  isRealNumber
} from '@/utils/reg'
const sliderRules = (() => {
  const validatorName = (rule: any, value: any, callback: any) => {
    if (value === '' || !value) {
      callback(new Error('请输入轮播图名称'))
    }
    callback()
  }

  const validatorSlider = (rule: any, value: any, callback: any) => {
    if (value === '' || !value) {
      callback(new Error('请选择轮播图'))
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
    name: [
      { required: true, validator: validatorName, trigger: 'blur' }
    ],
    slider: [
      { required: true, validator: validatorSlider, trigger: 'blur' }
    ],
    sort: [
      { validator: validatorSort, trigger: 'blur' }
    ]
  }
})()

export default sliderRules
