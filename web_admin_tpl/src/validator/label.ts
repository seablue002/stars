/* 标签 Validator */

const labelRules = (() => {
  const validatorName = (rule: any, value: any, callback: any) => {
    if (value === '') {
      callback(new Error('请输入标签名称'))
    }
    callback()
  }

  return {
    name: [
      { required: true, validator: validatorName, trigger: 'blur' }
    ]
  }
})()

export default labelRules
