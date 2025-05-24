/* Validator */
const labelRules = (() => {
  return {
    name: [{ required: true, message: '请输入标签名称', trigger: 'blur' }],
  }
})()

export default labelRules
