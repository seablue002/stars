/* 模板变量变量 Validator */
const tplVarRules = (() => {
  const validatorVarKey = (rule: any, value: any, callback: any) => {
    if (value === '') {
      callback(new Error('请输入模板变量key'))
    }
    callback()
  }

  const validatorVarName = (rule: any, value: any, callback: any) => {
    if (value === '') {
      callback(new Error('请输入模板变量名称'))
    }
    callback()
  }

  const validatorVarValue = (rule: any, value: any, callback: any) => {
    if (value === '' || value === '<p><br></p>') {
      callback(new Error('请输入模板变量内容'))
    }
    callback()
  }

  return {
    var_key: [
      { required: true, validator: validatorVarKey, trigger: 'blur' }
    ],
    var_name: [
      { required: true, validator: validatorVarName, trigger: 'blur' }
    ],
    var_value: [
      { required: true, validator: validatorVarValue, trigger: 'blur' }
    ]
  }
})()

export default tplVarRules
