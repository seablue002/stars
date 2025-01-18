/* 登录用户 Validator */
import {
  isArray
} from '@/utils'
const adminUserRules = (() => {
  const validatorUserName = (rule: any, value: any, callback: any) => {
    if (value.trim() === '') {
      callback(new Error('请输入用户名'))
    }
    callback()
  }

  const validatorPassword = (rule: any, value: any, callback: any) => {
    if (value.trim() === '') {
      callback(new Error('请输入密码'))
    }
    callback()
  }

  const validatorCode = (rule: any, value: any, callback: any) => {
    if (value.trim() === '') {
      callback(new Error('请输入验证码'))
    }
    callback()
  }

  return {
    username: [
      { required: true, validator: validatorUserName, trigger: 'blur' }
    ],
    password: [
      { required: true, validator: validatorPassword, trigger: 'blur' }
    ],
    code: [
      { required: true, validator: validatorCode, trigger: 'blur' }
    ]
  }
})()
export default adminUserRules

/* 密码修改 Validator */
export const modifyPasswordAdminUserRules = (() => {
  const validatorOldPassword = (rule: any, value: any, callback: any) => {
    if (value === '') {
      callback(new Error('请输入原密码'))
    }
    callback()
  }
  const validatorNewPassword = (rule: any, value: any, callback: any) => {
    if (value === '') {
      callback(new Error('请输入新密码'))
    }
    callback()
  }
  const validatorReNewPassword = (rule: any, value: any, callback: any) => {
    if (value === '') {
      callback(new Error('请再次输入新密码'))
    }
    callback()
  }
  return {
    oldPassword: [
      { required: true, validator: validatorOldPassword, trigger: 'blur' }
    ],
    newPassword: [
      { required: true, validator: validatorNewPassword, trigger: 'blur' }
    ],
    reNewPassword: [
      { required: true, validator: validatorReNewPassword, trigger: 'blur' }
    ]
  }
})()

/* 添加、修改管理员 Validator */
export const addAdminUserRules = (() => {
  const validatorName = (rule: any, value: any, callback: any) => {
    if (value === '') {
      callback(new Error('请输入用户名称'))
    }
    callback()
  }

  const validatorPWD = (rule: any, value: any, callback: any) => {
    if (value === '') {
      callback(new Error('请输入登录密码'))
    }
    callback()
  }

  const validatorRole = (rule: any, value: any, callback: any) => {
    if (!isArray(value) || value.length <= 0) {
      callback(new Error('请选择用户拥有的角色'))
    }
    callback()
  }

  const validatorStatus = (rule: any, value: any, callback: any) => {
    if (value === '') {
      callback(new Error('请选择账号状态'))
    }
    callback()
  }
  return {
    username: [
      { required: true, validator: validatorName, trigger: 'blur' }
    ],
    password: [
      { required: true, validator: validatorPWD, trigger: 'blur' }
    ],
    role: [
      { required: true, validator: validatorRole, trigger: 'blur' }
    ],
    status: [
      { required: true, validator: validatorStatus, trigger: 'blur' }
    ]
  }
})()
