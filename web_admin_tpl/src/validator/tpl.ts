/* 模板 Validator */
const tplRules = (() => {
  const validatorName = (rule: any, value: any, callback: any) => {
    if (value === '') {
      callback(new Error('请输入模板名称'))
    }
    callback()
  }

  const validatorContent = (rule: any, value: any, callback: any) => {
    if (value === '' || value === '<p><br></p>') {
      callback(new Error('请输入模板内容'))
    }
    callback()
  }

  return {
    name: [
      { required: true, validator: validatorName, trigger: 'blur' }
    ],
    content: [
      { required: true, validator: validatorContent, trigger: 'blur' }
    ]
  }
})()

export default tplRules
