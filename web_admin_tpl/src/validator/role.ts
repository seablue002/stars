/* 权限角色 Validator */
import {
  isArray
} from '@/utils'
const authRoleRules = (() => {
  const validatorName = (rule: any, value: any, callback: any) => {
    if (value === '') {
      callback(new Error('请输入角色名称'))
    }
    callback()
  }

  const validatorStatus = (rule: any, value: any, callback: any) => {
    if (value === '') {
      callback(new Error('请选择角色状态'))
    }
    callback()
  }

  const validatorRule = (rule: any, value: any, callback: any) => {
    if (!isArray(value) || value.length <= 0) {
      callback(new Error('请选择角色拥有的权限'))
    }
    callback()
  }

  return {
    title: [
      { required: true, validator: validatorName, trigger: 'blur' }
    ],
    status: [
      { required: true, validator: validatorStatus, trigger: 'blur' }
    ],
    rule: [
      { required: true, validator: validatorRule, trigger: 'blur' }
    ]
  }
})()

export default authRoleRules
