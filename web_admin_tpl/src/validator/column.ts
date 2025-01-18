/* 栏目 Validator */

const columnRules = (() => {
  const validatorName = (rule: any, value: any, callback: any) => {
    if (value === '') {
      callback(new Error('请输入栏目名称'))
    }
    callback()
  }

  const validatorModelTbNameName = (rule: any, value: any, callback: any) => {
    if (value === '') {
      callback(new Error('请选择绑定的数据模型'))
    }
    callback()
  }

  return {
    name: [
      { required: true, validator: validatorName, trigger: 'blur' }
    ],
    model_tb_name: [
      { required: true, validator: validatorModelTbNameName, trigger: 'blur' }
    ]
  }
})()

export default columnRules
