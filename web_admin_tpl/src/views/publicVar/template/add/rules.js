/* 表单校验项 Validator */
const validator = (() => {
  const validatorContent = (rule, value, callback) => {
    console.log(value, 'v123')
    if (value === '' || value === '<p><br></p>') {
      callback(new Error('请输入变量内容'))
    }
    callback()
  }

  return {
    var_key: [{ required: true, message: '请输入变量名称', trigger: 'blur' }],
    var_name: [{ required: true, message: '请输入变量标识', trigger: 'blur' }],
    var_value: [
      { required: true, validator: validatorContent, trigger: 'blur' },
    ],
  }
})()

export default validator
