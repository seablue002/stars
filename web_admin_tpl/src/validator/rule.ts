/* 权限规则 Validator */
const authRuleRules = (() => {
  const validatorName = (rule: any, value: any, callback: any) => {
    if (value === '') {
      callback(new Error('请输入规则PATH'))
    }
    callback()
  }

  const validatorTitle = (rule: any, value: any, callback: any) => {
    if (value === '') {
      callback(new Error('请输入规则名称'))
    }
    callback()
  }

  const validatorPid = (rule: any, value: any, callback: any) => {
    if (value === '') {
      callback(new Error('请选择父级规则'))
    }
    callback()
  }

  const validatorMenuType = (rule: any, value: any, callback: any) => {
    if (value === '') {
      callback(new Error('请选择规则类型'))
    }
    callback()
  }

  return {
    name: [
      { required: true, validator: validatorName, trigger: 'blur' }
    ],
    title: [
      { required: true, validator: validatorTitle, trigger: 'blur' }
    ],
    pid: [
      { required: true, validator: validatorPid, trigger: 'blur' }
    ],
    menu_type: [
      { required: true, validator: validatorMenuType, trigger: 'blur' }
    ]
  }
})()

export default authRuleRules
