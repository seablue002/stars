/* 表单校验项 Validator */
const validator = (() => {
  const validatorContent = (rule, value, callback) => {
    if (value === '' || value === '<p><br></p>') {
      callback(new Error('请输入模板内容'))
    }
    callback()
  }

  return {
    name: [{ required: true, message: '请输入模板名称', trigger: 'blur' }],
    content: [{ required: true, validator: validatorContent, trigger: 'blur' }],
  }
})()

export default validator
