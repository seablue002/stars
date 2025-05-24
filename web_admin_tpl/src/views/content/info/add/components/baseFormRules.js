/* 表单校验项 Validator */
const validator = (() => {
  const validatorTitle = (rule, value, callback) => {
    if (value === '') {
      callback(new Error('请输入标题'))
    }
    callback()
  }

  const validatorContent = (rule, value, callback) => {
    if (value === '' || !value || value === '<p><br></p>') {
      callback(new Error('请输入内容'))
    }
    callback()
  }

  return {
    title: [{ required: true, validator: validatorTitle, trigger: 'blur' }],
    column_id: [
      { required: true, message: '请选择所属栏目', trigger: 'change' },
    ],
    content: [
      { required: true, validator: validatorContent, trigger: 'change' },
    ],
  }
})()

export default validator
